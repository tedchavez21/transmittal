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
        Schema::table('records', function (Blueprint $table) {
            // Add encoder_id column as nullable foreign key
            $table->unsignedBigInteger('encoder_id')->nullable()->after('encoderName');
            
            // Add foreign key constraint (will reference users table later)
            // For now, we'll keep it without constraint until we have a unified users table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('records', function (Blueprint $table) {
            $table->dropColumn('encoder_id');
        });
    }
};
