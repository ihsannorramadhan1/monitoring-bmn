<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agenda;
use App\Models\Satker;
use App\Models\JenisPengelolaan;
use App\Models\User;
use App\Models\AgendaHistoryLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AgendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have dependencies
        if (Satker::count() == 0 || JenisPengelolaan::count() == 0 || User::count() == 0) {
            $this->command->error('Please seed Satker, JenisPengelolaan, and Users first.');
            return;
        }

        // Truncate tables to start fresh
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        AgendaHistoryLog::truncate();
        Agenda::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $satkers = Satker::pluck('id')->toArray();
        $jenisPengelolaans = JenisPengelolaan::all(); // Need target_hari
        $staffUsers = User::where('role', 'staff')->pluck('id')->toArray();

        // Fallback if no staff
        if (empty($staffUsers)) {
            $staffUsers = User::pluck('id')->toArray();
        }

        $faker = \Faker\Factory::create('id_ID');

        $statuses = ['masuk', 'verifikasi', 'review', 'disetujui', 'ditolak'];

        // Create 20 Agendas
        for ($i = 0; $i < 20; $i++) {
            DB::transaction(function () use ($faker, $satkers, $jenisPengelolaans, $staffUsers, $statuses, $i) {

                $jenis = $jenisPengelolaans->random();
                $satkerId = $faker->randomElement($satkers);
                $picId = $faker->randomElement($staffUsers);

                // Random date in last 6 months
                $tanggalMasuk = Carbon::now()->subDays(rand(0, 180));

                // Calculate target
                $targetHari = $jenis->target_hari;
                $tanggalTarget = $tanggalMasuk->copy()->addDays($targetHari);

                // Determine status with weighted probability for realism
                // More completed/processed items for better charts
                $rand = rand(1, 100);
                if ($rand <= 40)
                    $status = 'disetujui'; // 40%
                elseif ($rand <= 50)
                    $status = 'ditolak'; // 10%
                elseif ($rand <= 70)
                    $status = 'review'; // 20%
                elseif ($rand <= 85)
                    $status = 'verifikasi'; // 15%
                else
                    $status = 'masuk'; // 15%

                // Overdue logic
                if ($i % 10 == 0 && !in_array($status, ['disetujui', 'ditolak'])) {
                    // Force overdue every 10th item
                    $tanggalMasuk = Carbon::now()->subDays($targetHari + rand(5, 20));
                    $tanggalTarget = $tanggalMasuk->copy()->addDays($targetHari);
                }

                $tanggalSelesai = null;
                $durasiHari = null;

                if (in_array($status, ['disetujui', 'ditolak'])) {
                    // Completed items
                    $isLate = rand(0, 1) == 1;
                    if ($isLate) {
                        $tanggalSelesai = $tanggalTarget->copy()->addDays(rand(1, 10));
                    } else {
                        $tanggalSelesai = $tanggalTarget->copy()->subDays(rand(0, $targetHari - 1));
                    }

                    if ($tanggalSelesai->lt($tanggalMasuk)) {
                        $tanggalSelesai = $tanggalMasuk->copy()->addDays(1);
                    }

                    $durasiHari = $tanggalMasuk->diffInDays($tanggalSelesai);
                } else {
                    $durasiHari = $tanggalMasuk->diffInDays(Carbon::now());
                }

                $agenda = Agenda::create([
                    'nomor_agenda' => 'AGENDA-' . date('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'satker_id' => $satkerId,
                    'jenis_pengelolaan_id' => $jenis->id,
                    'tanggal_masuk' => $tanggalMasuk,
                    'tanggal_target' => $tanggalTarget,
                    'tanggal_selesai' => $tanggalSelesai,
                    'status' => $status,
                    'notes' => $faker->sentence(),
                    'pic_id' => $picId,
                    'file_uploads' => json_encode([]),
                    'durasi_hari' => $durasiHari,
                    'created_by' => $picId,
                ]);

                // Create History Logs
                AgendaHistoryLog::create([
                    'agenda_id' => $agenda->id,
                    'changed_by' => $picId,
                    'status_old' => null,
                    'status_new' => 'masuk',
                    'notes' => 'Agenda baru dibuat',
                    'created_at' => $tanggalMasuk,
                ]);

                // If status advanced, add intermediate logs
                $flow = ['masuk', 'verifikasi', 'review', 'disetujui'];
                if ($status == 'ditolak')
                    $flow = ['masuk', 'verifikasi', 'ditolak'];

                $currentStepDate = $tanggalMasuk->copy();

                foreach ($flow as $index => $step) {
                    if ($step == 'masuk')
                        continue;
                    if ($step == $status || array_search($step, $flow) < array_search($status, $flow)) {

                        $currentStepDate->addDays(rand(1, 3));
                        if ($currentStepDate->gt(Carbon::now()))
                            $currentStepDate = Carbon::now();

                        AgendaHistoryLog::create([
                            'agenda_id' => $agenda->id,
                            'changed_by' => $picId,
                            'status_old' => $flow[$index - 1],
                            'status_new' => $step,
                            'notes' => 'Status updated to ' . ucfirst($step),
                            'created_at' => $currentStepDate,
                        ]);
                    }
                }
            });
        }
    }
}
