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
        DB::table('email_handlers')->where('id', 1)->update(['name' => 'Uzziel Martinez Sr', 'approved' => true, 'active' => true]);
        DB::table('email_handlers')->where('id', 2)->update(['name' => 'Teddy', 'approved' => false, 'active' => false]);
        DB::table('email_handlers')->where('id', 3)->update(['name' => 'Juvielyn Fiesta', 'approved' => false, 'active' => false]);
        DB::table('email_handlers')->where('id', 4)->update(['name' => 'Hanna Marie Lorica', 'approved' => true, 'active' => true]);
        DB::table('email_handlers')->where('id', 5)->update(['name' => 'Julie Ann Espejo', 'approved' => true, 'active' => true]);
        DB::table('email_handlers')->where('id', 6)->update(['name' => 'Teddyboi', 'approved' => true, 'active' => true]);
        DB::table('email_handlers')->where('id', 7)->update(['name' => 'Uzziel Martinez', 'approved' => false, 'active' => false]);
        DB::table('email_handlers')->where('id', 8)->update(['name' => 'Ted', 'approved' => false, 'active' => false]);
        DB::table('email_handlers')->where('id', 9)->update(['name' => 'Teddy Tan', 'approved' => false, 'active' => false]);
        DB::table('email_handlers')->where('id', 10)->update(['name' => 'Hanna Lorica', 'approved' => true, 'active' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
