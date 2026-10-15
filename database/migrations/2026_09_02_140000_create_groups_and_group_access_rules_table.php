<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('access_mode')->default('custom'); // all, categories, custom
            $table->timestamps();
        });

        Schema::create('group_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['group_id', 'user_id']);
        });

        Schema::create('group_access_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->unsignedBigInteger('accessible_id');
            $table->string('accessible_type');
            $table->timestamps();

            $table->index(['group_id', 'accessible_id', 'accessible_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_access_rules');
        Schema::dropIfExists('group_user');
        Schema::dropIfExists('groups');
    }
};
