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
        // Update specific encoder_name mappings
        DB::table('records')->where('source', 'Email')->where('encoderName', 'Ted')->whereNull('encoder_id')->update(['encoder_id' => 2]); // Ted Eiden Chavez
        DB::table('records')->where('source', 'Email')->where('encoderName', 'Hanna Marie Lorica')->whereNull('encoder_id')->update(['encoder_id' => 4]); // Hanna Marie A. Lorica
        DB::table('records')->where('source', 'Email')->where('encoderName', 'Julie Ann Espejo')->whereNull('encoder_id')->update(['encoder_id' => 5]); // Julie Ann V. Espejo
        
        // Update any remaining exact matches
        DB::statement('
            UPDATE records r 
            SET r.encoder_id = (
                SELECT o.id 
                FROM officers o 
                WHERE o.name = r.encoderName
                LIMIT 1
            )
            WHERE r.source = "Email" 
            AND r.encoder_id IS NULL
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
