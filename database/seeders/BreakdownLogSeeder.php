<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BreakdownLog;
use App\Models\Infrastructure;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class BreakdownLogSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $infrastructures = Infrastructure::all();
        $adminUsers = User::whereIn('role', ['superadmin', 'operator'])->get();
        
        $repairStatuses = ['reported', 'order_part', 'on_progress', 'resolved'];
        
        $issues = [
            'equipment' => [
                'Mesin overheat setelah operasi 2 jam.',
                'Kebocoran oli hidrolik pada silinder utama.',
                'Kabel sling putus saat mengangkat beban berat.',
                'Sistem elektrikal mati total.',
                'Sensor proximity tidak merespon.',
                'Suara kasar dari gearbox.',
                'Indikator error pada dashboard operator.',
                'Kampas rem aus dan butuh penggantian.',
                'AC kabin tidak dingin.',
                'Radiator bocor dan cairan pendingin habis.'
            ],
            'facility' => [
                'Atap gudang bocor menyebabkan genangan air.',
                'Pintu rolling door macet tidak bisa dibuka.',
                'Pagar pembatas roboh tertabrak truk.',
                'Lantai beton retak parah di area penumpukan.',
                'Sistem ventilasi tidak berfungsi optimal.',
                'Saluran pembuangan air tersumbat.',
                'Lampu penerangan dalam gudang banyak yang mati.'
            ],
            'utility' => [
                'Genset gagal start otomatis saat listrik PLN padam.',
                'Tegangan tidak stabil dari gardu distribusi.',
                'Pompa hydrant tidak mengeluarkan tekanan yang cukup.',
                'Kebocoran pipa air bersih utama.',
                'Panel surya tidak mengisi daya ke baterai.',
                'Kompresor kehilangan tekanan secara perlahan.'
            ],
            'marine' => [
                'Mesin induk (main engine) mati mendadak saat manuver.',
                'Kebocoran pada lambung kapal sebelah kiri.',
                'Propeller terbelit jaring nelayan atau tali mooring.',
                'Sistem navigasi dan radar blank.',
                'Genset kapal (auxiliary engine) tidak berfungsi.',
                'Kerusakan pada sistem kemudi (steering gear).',
                'Pompa bilga macet sehingga air masuk.',
                'Winch penarik jangkar tidak bisa berputar.'
            ]
        ];

        $vendors = [
            'PT Global Teknik Mandiri', 'CV Maju Jaya Perkasa', 'PT Indo Machine Sejahtera',
            'Tim Teknisi Internal', 'PT Bima Solusi Elektrik', 'PT Pelindo Daya Sejahtera'
        ];

        foreach ($infrastructures as $infra) {
            // Berapa kali alat ini pernah rusak? (0-5 kali histori)
            $historyCount = rand(0, 5);
            
            // Ambil operator yang sesuai dengan entitas infrastruktur
            $operator = User::where('entity_id', $infra->entity_id)->inRandomOrder()->first();
            $creatorId = $operator ? $operator->id : $adminUsers->random()->id;
            
            // To make it look more real, sometimes the updater is different
            $updater = User::where('entity_id', $infra->entity_id)->inRandomOrder()->first();
            $updaterId = $updater ? $updater->id : $creatorId;

            // Jika status saat ini breakdown, pastikan ada 1 log yang belum resolved
            if ($infra->status === 'breakdown') {
                $statusIndex = rand(0, 2); // reported, order_part, on_progress
                $status = $repairStatuses[$statusIndex];
                
                $createdDate = Carbon::now()->subDays(rand(1, 30));
                
                // Tambah tanggal progres
                $troubleshoot_date = $statusIndex >= 0 ? (clone $createdDate)->addHours(rand(2, 24)) : null;
                $ba_date = $statusIndex >= 1 ? (clone $troubleshoot_date)->addDays(rand(1, 3)) : null;
                $work_order_date = $statusIndex >= 1 ? (clone $ba_date)->addDays(rand(1, 2)) : null;
                $pr_po_date = $statusIndex >= 1 ? (clone $work_order_date)->addDays(rand(2, 5)) : null;
                $start_work_date = $statusIndex >= 2 ? (clone $pr_po_date)->addDays(rand(1, 7)) : null;
                
                $log = BreakdownLog::create([
                    'infrastructure_id' => $infra->id,
                    'issue_detail' => $faker->randomElement($issues[$infra->category]),
                    'repair_status' => $status,
                    'vendor_pic' => $faker->randomElement($vendors),
                    'troubleshoot_date' => $troubleshoot_date,
                    'ba_date' => $ba_date,
                    'work_order_date' => $work_order_date,
                    'pr_po_date' => $pr_po_date,
                    'sparepart_date' => null,
                    'start_work_date' => $start_work_date,
                    'com_test_date' => null,
                    'resolved_date' => null,
                    'created_by' => $creatorId,
                    'updated_by' => $updaterId,
                    'created_at' => $createdDate,
                    'updated_at' => Carbon::now()
                ]);

                // Audit Trail (Status History)
                \App\Models\StatusHistory::create([
                    'breakdown_log_id' => $log->id,
                    'old_status' => null,
                    'new_status' => 'reported',
                    'note' => 'Laporan kerusakan awal oleh sistem/operator.',
                    'user_id' => $creatorId,
                    'created_at' => $createdDate,
                ]);

                if ($statusIndex >= 1) {
                    \App\Models\StatusHistory::create([
                        'breakdown_log_id' => $log->id,
                        'old_status' => 'reported',
                        'new_status' => 'order_part',
                        'note' => 'Menunggu pemesanan sparepart.',
                        'user_id' => $updaterId,
                        'created_at' => $pr_po_date,
                    ]);
                }

                if ($statusIndex >= 2) {
                    \App\Models\StatusHistory::create([
                        'breakdown_log_id' => $log->id,
                        'old_status' => 'order_part',
                        'new_status' => 'on_progress',
                        'note' => 'Pekerjaan perbaikan dimulai.',
                        'user_id' => $updaterId,
                        'created_at' => $start_work_date,
                    ]);
                }
            }
            
            // Generate historical data (already resolved)
            for ($i = 0; $i < $historyCount; $i++) {
                // Rentang waktu: 2 tahun lalu hingga 1 bulan lalu
                $createdDate = Carbon::now()->subDays(rand(30, 730));
                $troubleshoot_date = (clone $createdDate)->addHours(rand(2, 48));
                $ba_date = (clone $troubleshoot_date)->addDays(rand(1, 3));
                $work_order_date = (clone $ba_date)->addDays(rand(1, 2));
                $pr_po_date = (clone $work_order_date)->addDays(rand(2, 10));
                $sparepart_date = (clone $pr_po_date)->addDays(rand(3, 14));
                $start_work_date = (clone $sparepart_date)->addDays(rand(1, 3));
                $com_test_date = (clone $start_work_date)->addDays(rand(2, 7));
                $resolved_date = (clone $com_test_date)->addDays(1);
                
                $log = BreakdownLog::create([
                    'infrastructure_id' => $infra->id,
                    'issue_detail' => $faker->randomElement($issues[$infra->category]),
                    'repair_status' => 'resolved',
                    'vendor_pic' => $faker->randomElement($vendors),
                    'troubleshoot_date' => $troubleshoot_date,
                    'ba_date' => $ba_date,
                    'work_order_date' => $work_order_date,
                    'pr_po_date' => $pr_po_date,
                    'sparepart_date' => $sparepart_date,
                    'start_work_date' => $start_work_date,
                    'com_test_date' => $com_test_date,
                    'resolved_date' => $resolved_date,
                    'created_by' => $creatorId,
                    'updated_by' => $updaterId,
                    'created_at' => $createdDate,
                    'updated_at' => $resolved_date
                ]);

                // Audit Trail for resolved
                \App\Models\StatusHistory::create([
                    'breakdown_log_id' => $log->id,
                    'old_status' => null,
                    'new_status' => 'reported',
                    'note' => 'Laporan kerusakan awal.',
                    'user_id' => $creatorId,
                    'created_at' => $createdDate,
                ]);

                \App\Models\StatusHistory::create([
                    'breakdown_log_id' => $log->id,
                    'old_status' => 'reported',
                    'new_status' => 'order_part',
                    'note' => 'Sparepart dipesan.',
                    'user_id' => $updaterId,
                    'created_at' => $pr_po_date,
                ]);

                \App\Models\StatusHistory::create([
                    'breakdown_log_id' => $log->id,
                    'old_status' => 'order_part',
                    'new_status' => 'on_progress',
                    'note' => 'Proses perbaikan.',
                    'user_id' => $updaterId,
                    'created_at' => $start_work_date,
                ]);

                \App\Models\StatusHistory::create([
                    'breakdown_log_id' => $log->id,
                    'old_status' => 'on_progress',
                    'new_status' => 'resolved',
                    'note' => 'Perbaikan selesai, unit kembali ready.',
                    'user_id' => $updaterId,
                    'created_at' => $resolved_date,
                ]);
            }
        }
    }
}
