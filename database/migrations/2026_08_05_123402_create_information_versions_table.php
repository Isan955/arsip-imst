<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('information_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('information_id')->constrained('informations')->onDelete('cascade');
            $table->integer('month'); // 1-12
            $table->integer('year'); // e.g. 2026
            $table->longText('rich_content');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Ensure unique version per month/year per information entity
            $table->unique(['information_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('information_versions');
    }
};
