<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BreakdownLog;
use App\Models\Infrastructure;

class BreakdownLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mencari alat yang statusnya breakdown
        $glc01 = Infrastructure::where('code_name', 'GLC-01')->first();
        $gen01 = Infrastructure::where('code_name', 'GEN-01')->first();

        if ($glc01) {
            BreakdownLog::create([
                'infrastructure_id' => $glc01->id,
                'issue_detail' => 'Motor gantry rusak dan sistem hidrolik bocor.',
                'vendor_pic' => 'PT. BIMA',
                'repair_status' => 'order_part',
            ]);
        }

        if ($gen01) {
            BreakdownLog::create([
                'infrastructure_id' => $gen01->id,
                'issue_detail' => 'Overheating pada radiator utama.',
                'vendor_pic' => 'Tim Teknisi Internal',
                'repair_status' => 'on_progress',
            ]);
        }
    }
}
