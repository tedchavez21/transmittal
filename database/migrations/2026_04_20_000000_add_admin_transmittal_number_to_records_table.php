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
            $table->string('admin_transmittal_number')->nullable()->after('transmittal_number');
            $table->timestamp('admin_transmittal_assigned_at')->nullable()->after('admin_transmittal_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('records', function (Blueprint $table) {
            $table->dropColumn(['admin_transmittal_number', 'admin_transmittal_assigned_at']);
        });
    }
};
