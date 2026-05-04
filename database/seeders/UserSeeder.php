<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Superadmins (Pusat)
        User::create([
            'name' => 'Administrator Pusat (Teknik)',
            'email' => 'admin.pusat@pelindo.co.id',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'General Manager Regional',
            'email' => 'gm.regional@pelindo.co.id',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'email_verified_at' => now(),
        ]);

        // 2. Operators - Tanjung Priok (PTP)
        $ptp = \App\Models\Entity::where('code', 'PTP')->first();
        if ($ptp) {
            User::create([
                'name' => 'Supervisor Teknik PTP',
                'email' => 'spv.ptp@pelindo.co.id',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'entity_id' => $ptp->id,
                'email_verified_at' => now(),
            ]);

            User::create([
                'name' => 'Operator Lapangan PTP',
                'email' => 'ops.ptp@pelindo.co.id',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'entity_id' => $ptp->id,
                'email_verified_at' => now(),
            ]);
        }

        // 3. Operators - TPS Surabaya
        $tps = \App\Models\Entity::where('code', 'TPS')->first();
        if ($tps) {
            User::create([
                'name' => 'Ast. Manager Maintenance TPS',
                'email' => 'asman.tps@pelindo.co.id',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'entity_id' => $tps->id,
                'email_verified_at' => now(),
            ]);
        }

        // 4. Operators - Belawan / Other
        $belawan = \App\Models\Entity::where('code', 'BICT')->first() ?? \App\Models\Entity::where('id', '!=', $ptp->id ?? 0)->first();
        if ($belawan) {
            User::create([
                'name' => 'Teknisi Belawan',
                'email' => 'teknisi.belawan@pelindo.co.id',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'entity_id' => $belawan->id,
                'email_verified_at' => now(),
            ]);
        }
    }
}
