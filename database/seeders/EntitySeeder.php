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
            ['name' => 'PT TERMINAL PETIKEMAS', 'code' => 'TPK'],
            ['name' => 'PT PELABUHAN TANJUNG PRIOK', 'code' => 'PTP'],
            ['name' => 'PT PELINDO JASA MARITIM', 'code' => 'PJM'],
        ];

        foreach ($entities as $entity) {
            Entity::create($entity);
        }
    }
}
