<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Officer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hash all existing plain text passwords
        Officer::whereNotNull('password')->chunk(100, function ($officers) {
            foreach ($officers as $officer) {
                // Check if password is already hashed (starts with $2y$, $2a$, etc.)
                if (!str_starts_with($officer->password, '$2')) {
                    $officer->password = Hash::make($officer->password);
                    $officer->save();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: We cannot reverse this migration as we cannot unhash passwords
        // This is a one-way migration for security purposes
    }
};
