<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entity;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = [
            ['name' => 'TPK TANJUNG PRIOK', 'code' => 'PTP'],
            ['name' => 'TPK SURABAYA (TPS)', 'code' => 'TPS'],
            ['name' => 'TPK BELAWAN (BICT)', 'code' => 'BICT'],
            ['name' => 'TPK SEMARANG (TPKS)', 'code' => 'TPKS'],
            ['name' => 'TPK MAKASSAR', 'code' => 'TPKM'],
            ['name' => 'REGIONAL 2 PANJANG', 'code' => 'PANJ'],
            ['name' => 'REGIONAL 2 PALEMBANG', 'code' => 'PLM'],
            ['name' => 'REGIONAL 2 PONTIANAK', 'code' => 'PTN'],
        ];

        foreach ($entities as $entity) {
            \App\Models\Entity::create($entity);
        }
    }
}
