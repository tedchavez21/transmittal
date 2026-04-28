<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('officers', function (Blueprint $table) {
            // Remove approved and approved_at columns
            $table->dropColumn(['approved', 'approved_at']);
            
            // Add username and password columns
            $table->string('username')->unique()->after('name');
            $table->string('password')->after('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('officers', function (Blueprint $table) {
            // Add back approved and approved_at columns
            $table->tinyInteger('approved')->default(0)->after('username');
            $table->timestamp('approved_at')->nullable()->after('approved');
            
            // Remove username and password columns
            $table->dropColumn(['username', 'password']);
        });
    }
};
