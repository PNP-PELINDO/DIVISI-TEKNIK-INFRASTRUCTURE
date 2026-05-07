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

        // 4. Generate Operators for ALL remaining entities
        $allEntities = \App\Models\Entity::all();
        foreach ($allEntities as $entity) {
            // Check if this entity already has users from the specific seeds above
            if (User::where('entity_id', $entity->id)->exists()) continue;

            $slug = strtolower(str_replace(' ', '.', $entity->name));
            User::create([
                'name' => 'Operator ' . $entity->name,
                'email' => "ops.{$slug}@pelindo.co.id",
                'password' => Hash::make('password'),
                'role' => 'operator',
                'entity_id' => $entity->id,
                'email_verified_at' => now(),
            ]);
        }
    }
}
