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
        if (!Schema::hasColumn('records', 'date_occurrence')) {
            Schema::table('records', function (Blueprint $table) {
                $table->date('date_occurrence')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('records', 'date_occurrence')) {
            Schema::table('records', function (Blueprint $table) {
                $table->dropColumn('date_occurrence');
            });
        }
    }
};
