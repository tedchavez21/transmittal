<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_handlers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_handlers');
    }
};
