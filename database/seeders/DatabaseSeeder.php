<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $records = [];
        
        $provinces = ['Ilocos Norte', 'Ilocos Sur', 'La Union', 'Pangasinan'];
        $municipalities = ['San Fernando', 'Baguio', 'Laoag', 'Vigan', 'Dagupan'];
        $barangays = ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5'];
        $programs = ['RSBSA', 'SFFA', 'FFA', 'AGRI'];
        $lines = ['Line 1', 'Line 2', 'Line 3'];
        $causes = ['Flood', 'Drought', 'Pest', 'Disease', 'Storm'];
        $sources = ['OD', 'Email', 'Facebook'];
        
        for ($i = 1; $i <= 56; $i++) {
            $records[] = [
                'farmerName' => "Farmer $i",
                'address' => "Address $i",
                'province' => $provinces[array_rand($provinces)],
                'municipality' => $municipalities[array_rand($municipalities)],
                'barangay' => $barangays[array_rand($barangays)],
                'program' => $programs[array_rand($programs)],
                'line' => $lines[array_rand($lines)],
                'causeOfDamage' => $causes[array_rand($causes)],
                'modeOfPayment' => 'Cash',
                'remarks' => "Remarks for record $i",
                'source' => $sources[array_rand($sources)],
                'encoderName' => 'Admin',
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ];
        }
        
        foreach (array_chunk($records, 10) as $chunk) {
            \App\Models\Record::insert($chunk);
        }
        
        echo "Created 56 records.\n";
    }
}
