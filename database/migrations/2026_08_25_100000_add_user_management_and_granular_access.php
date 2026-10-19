<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('password');
            $table->string('access_mode')->default('all')->after('is_active'); // all, categories, custom
        });

        Schema::create('user_access_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('accessible_id');
            $table->string('accessible_type');
            $table->timestamps();

            $table->index(['user_id', 'accessible_id', 'accessible_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_access_rules');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'access_mode']);
        });
    }
};
