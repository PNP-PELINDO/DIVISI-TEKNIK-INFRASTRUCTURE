<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Infrastructure;
use App\Models\Entity;

class InfrastructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tpk = Entity::where('code', 'TPK')->first();
        $ptp = Entity::where('code', 'PTP')->first();
        $pjm = Entity::where('code', 'PJM')->first();

        $infrastructures = [
            // --- PT TERMINAL PETIKEMAS (EQUIPMENT) ---
            ['entity_id' => $tpk->id, 'category' => 'equipment', 'type' => 'Gantry Luffing Crane', 'code_name' => 'GLC-01', 'status' => 'breakdown'],
            ['entity_id' => $tpk->id, 'category' => 'equipment', 'type' => 'Gantry Luffing Crane', 'code_name' => 'GLC-02', 'status' => 'available'],
            ['entity_id' => $tpk->id, 'category' => 'equipment', 'type' => 'Rubber Tired Gantry', 'code_name' => 'RTG-01', 'status' => 'available'],
            ['entity_id' => $tpk->id, 'category' => 'equipment', 'type' => 'Reach Stacker', 'code_name' => 'RS-01', 'status' => 'available'],
            ['entity_id' => $tpk->id, 'category' => 'equipment', 'type' => 'Head Truck', 'code_name' => 'HT-01', 'status' => 'available'],
            ['entity_id' => $tpk->id, 'category' => 'equipment', 'type' => 'Chassis Trailer', 'code_name' => 'CH-01', 'status' => 'available'],
            ['entity_id' => $tpk->id, 'category' => 'equipment', 'type' => 'Forklift', 'code_name' => 'FL-01', 'status' => 'available'],
            
            // --- PT PELABUHAN TANJUNG PRIOK (EQUIPMENT & FACILITY) ---
            ['entity_id' => $ptp->id, 'category' => 'equipment', 'type' => 'Excavator', 'code_name' => 'EXC-01', 'status' => 'available'],
            ['entity_id' => $ptp->id, 'category' => 'equipment', 'type' => 'Wheel Loader', 'code_name' => 'WL-01', 'status' => 'available'],
            ['entity_id' => $ptp->id, 'category' => 'facility', 'type' => 'Gudang Penumpukan', 'code_name' => 'GDG-A', 'status' => 'available'],
            ['entity_id' => $ptp->id, 'category' => 'utility', 'type' => 'Genset Utama', 'code_name' => 'GEN-01', 'status' => 'breakdown'],

            // --- PT PELINDO JASA MARITIM (EQUIPMENT) ---
            ['entity_id' => $pjm->id, 'category' => 'equipment', 'type' => 'Kapal Tunda', 'code_name' => 'KT-01', 'status' => 'available'],
            ['entity_id' => $pjm->id, 'category' => 'equipment', 'type' => 'Kapal Pandu', 'code_name' => 'KP-01', 'status' => 'available'],
        ];

        foreach ($infrastructures as $infra) {
            Infrastructure::create($infra);
        }
    }
}
