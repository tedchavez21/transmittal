<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update encoder_id for Glen Bondoc records
        DB::table('records')->where('encoderName', 'Glen Bondoc')->whereNull('encoder_id')->update(['encoder_id' => 10]); // Glen C. Bondoc
        
        // Update any remaining records that match officer names exactly
        DB::statement('
            UPDATE records r 
            SET r.encoder_id = (
                SELECT o.id 
                FROM officers o 
                WHERE o.name = r.encoderName
                LIMIT 1
            )
            WHERE r.encoder_id IS NULL
            AND r.encoderName IS NOT NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
