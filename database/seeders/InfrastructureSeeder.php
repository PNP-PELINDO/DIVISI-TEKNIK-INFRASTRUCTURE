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
            ],
            'marine' => [
                'Tug Boat', 'Pilot Boat', 'Mooring Boat', 'Patrol Boat',
                'Dredger', 'Floating Crane', 'Tongkang', 'Speedboat'
            ]
        ];

        foreach ($entities as $entity) {
            // Berikan 20-40 aset per entitas untuk data yang lebih padat
            $assetCount = rand(20, 40);

            for ($i = 0; $i < $assetCount; $i++) {
                // Ambil user operator dari entitas ini secara acak per aset
                $operator = \App\Models\User::where('entity_id', $entity->id)->inRandomOrder()->first();
                $creatorId = $operator ? $operator->id : 1;
                
                // Jika alat sedang breakdown/diperbarui, mungkin yang update berbeda (opsional)
                $updater = \App\Models\User::where('entity_id', $entity->id)->inRandomOrder()->first();
                $updaterId = $updater ? $updater->id : $creatorId;

                $category = $faker->randomElement(array_keys($categories));
                $type = $faker->randomElement($categories[$category]);

                // Buat kode unik sesuai standar (menggunakan $i agar berurutan dan terhindar dari duplikat)
                $prefix = strtoupper(substr(str_replace(' ', '', $type), 0, 3));
                $codeName = $prefix . '-' . $entity->code . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);

                // 15% kemungkinan rusak
                $status = (rand(1, 100) <= 15) ? 'breakdown' : 'available';

                // Jumlah unit (variatif)
                $quantity = ($category === 'equipment' || $category === 'marine') ? 1 : rand(1, 10);

                // Keyword aman untuk LoremFlickr agar terhindar dari gambar negatif/tidak pantas
                $safeKeywords = [
                    'equipment' => 'excavator,forklift',
                    'facility'  => 'warehouse,container',
                    'utility'   => 'generator,engine',
                    'marine'    => 'cargoship,tugboat'
                ];
                
                // Gunakan lock berdasarkan ID entity dan index agar gambar tetap konsisten dan aman dari rate-limit (dibanding Pexels)
                $lockId = $entity->id * 100 + $i;
                $keyword = $safeKeywords[$category];
                $imageUrl = "https://loremflickr.com/600/400/{$keyword}?lock={$lockId}";

                $infrastructures[] = [
                    'entity_id'  => $entity->id,
                    'category'   => $category,
                    'type'       => $type,
                    'code_name'  => $codeName,
                    'status'     => $status,
                    'quantity'   => $quantity,
                    'image'      => $imageUrl,
                    'created_by' => $creatorId,
                    'updated_by' => $updaterId,
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
