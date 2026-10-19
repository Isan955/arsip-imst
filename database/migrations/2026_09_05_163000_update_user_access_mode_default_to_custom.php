<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('access_mode')->default('custom')->change();
        });

        // Set default viewer accounts to custom if they are not admin
        DB::table('users')
            ->where('email', 'viewer@imst.com')
            ->update(['access_mode' => 'custom']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('access_mode')->default('all')->change();
        });
    }
};
