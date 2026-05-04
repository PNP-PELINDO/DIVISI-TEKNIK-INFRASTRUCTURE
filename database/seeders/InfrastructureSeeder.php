<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Infrastructure;
use App\Models\Entity;
use Faker\Factory as Faker;

class InfrastructureSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $entities = Entity::all();

        $categories = [
            'equipment' => [
                'Gantry Luffing Crane', 'Rubber Tired Gantry', 'Reach Stacker',
                'Head Truck', 'Chassis Trailer', 'Forklift', 'Excavator',
                'Wheel Loader', 'Mobile Crane', 'Straddle Carrier',
                'Quay Crane', 'Harbour Mobile Crane', 'Towing Tractor'
            ],
            'facility' => [
                'Gudang Penumpukan', 'Silo', 'Tangki Timbun', 'Lapangan Penumpukan',
                'Area Konsolidasi', 'Gedung Operasional', 'Pos Jaga', 'Workshop Alat Berat',
                'Dermaga', 'Fasilitas Air Bersih'
            ],
            'utility' => [
                'Genset Utama', 'Genset Cadangan', 'Sistem Pompa Air', 'Gardu Listrik',
                'Panel Distribusi', 'Sistem Kompresor', 'Penerangan Jalan', 'Sistem Pemadam Kebakaran'
            ]
        ];

        $infrastructures = [];

        foreach ($entities as $entity) {
            // Berikan 15-30 aset per entitas untuk data yang lebih padat
            $assetCount = rand(15, 30);

            for ($i = 0; $i < $assetCount; $i++) {
                $category = $faker->randomElement(array_keys($categories));
                $type = $faker->randomElement($categories[$category]);

                // Buat kode unik sesuai standar
                $prefix = strtoupper(substr(str_replace(' ', '', $type), 0, 3));
                $codeName = $prefix . '-' . $entity->code . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

                // 15% kemungkinan rusak
                $status = (rand(1, 100) <= 15) ? 'breakdown' : 'available';

                // Jumlah unit (variatif)
                $quantity = ($category === 'equipment') ? 1 : rand(1, 10);

                // Generate Dummy Image URL
                $imageText = urlencode($type . ' | ' . $codeName);
                $imageUrl = "https://placehold.co/600x400/003366/ffffff?text=" . $imageText;

                $infrastructures[] = [
                    'entity_id'  => $entity->id,
                    'category'   => $category,
                    'type'       => $type,
                    'code_name'  => $codeName,
                    'status'     => $status,
                    'quantity'   => $quantity,
                    'image'      => $imageUrl,
                    'created_by' => 1, // Admin Pusat
                    'created_at' => now()->subMonths(rand(1, 12)),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert in chunks to be safe
        foreach (array_chunk($infrastructures, 50) as $chunk) {
            Infrastructure::insert($chunk);
        }
    }
}
