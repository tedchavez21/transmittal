<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('officers', function (Blueprint $table) {
            if (!Schema::hasColumn('officers', 'active')) {
                $table->boolean('active')->default(false)->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('officers', function (Blueprint $table) {
            if (Schema::hasColumn('officers', 'active')) {
                $table->dropColumn('active');
            }
        });
    }
};
