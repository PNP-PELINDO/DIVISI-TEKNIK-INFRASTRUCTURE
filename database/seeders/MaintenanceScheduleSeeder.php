<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceSchedule;
use App\Models\Infrastructure;
use App\Models\User;
use Carbon\Carbon;

class MaintenanceScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $infrastructures = Infrastructure::all();
        $admin = User::where('role', 'superadmin')->first();

        $titles = [
            'Preventive Maintenance Bulanan',
            'Sertifikasi & Uji Kelayakan',
            'Greasing & Lubrication Rutin',
            'Pengecekan Sistem Elektrikal',
            'Kalibrasi Sensor & Proximity',
            'Overhaul Ringan Mesin Utama',
            'Pengecekan Struktur Baja',
            'Penggantian Filter & Oli'
        ];

        foreach ($infrastructures as $infra) {
            // Berikan 1-3 jadwal per alat
            $count = rand(1, 3);

            for ($i = 0; $i < $count; $i++) {
                // Ada yang sudah lewat (completed), ada yang akan datang (scheduled)
                $type = rand(0, 1) ? 'past' : 'future';
                
                if ($type === 'past') {
                    $date = Carbon::now()->subDays(rand(1, 60));
                    $status = rand(0, 5) === 0 ? 'cancelled' : 'completed';
                } else {
                    $date = Carbon::now()->addDays(rand(1, 30));
                    $status = 'scheduled';
                }

                MaintenanceSchedule::create([
                    'infrastructure_id' => $infra->id,
                    'title' => $titles[array_rand($titles)],
                    'description' => 'Pekerjaan rutin pemeliharaan untuk memastikan kesiapan alat sesuai standar operasional Pelindo.',
                    'scheduled_date' => $date,
                    'status' => $status,
                    'created_by' => $admin->id ?? 1,
                ]);
            }
        }
    }
}
