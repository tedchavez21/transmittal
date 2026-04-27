<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Set non-date values to NULL to prevent conversion errors
        DB::statement("UPDATE records SET date_occurrence = NULL WHERE date_occurrence = '' OR date_occurrence IS NULL");
        DB::statement("UPDATE records SET date_occurrence = NULL WHERE date_occurrence NOT REGEXP '^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$' AND date_occurrence NOT REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'");
        
        // Convert dates in format 'm/d/Y' to 'Y-m-d'
        DB::statement("UPDATE records SET date_occurrence = STR_TO_DATE(date_occurrence, '%m/%d/%Y') WHERE date_occurrence REGEXP '^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$'");
        
        // Now change the column type
        Schema::table('records', function (Blueprint $table) {
            $table->date('date_occurrence')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('records', function (Blueprint $table) {
            $table->string('date_occurrence', 500)->nullable()->change();
        });
    }
};
