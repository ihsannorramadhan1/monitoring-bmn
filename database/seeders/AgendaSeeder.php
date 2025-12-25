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

                // Determine status first to generate contextual dates
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

                $targetHari = $jenis->target_hari;
                $tanggalSelesai = null;
                $durasiHari = null;

                // Date Generation Logic
                if (in_array($status, ['disetujui', 'ditolak'])) {
                    // COMPLETED: Can be historical (past 6 months)
                    $tanggalMasuk = Carbon::now()->subDays(rand($targetHari + 2, 180));
                    $tanggalTarget = $tanggalMasuk->copy()->addDays($targetHari);

                    // Determine if finished early, on time, or late
                    $finishScenario = rand(1, 100);
                    if ($finishScenario <= 60) {
                        // 60% On time or early
                        $tanggalSelesai = $tanggalTarget->copy()->subDays(rand(0, $targetHari - 1));
                    } else {
                        // 40% Late
                        $tanggalSelesai = $tanggalTarget->copy()->addDays(rand(1, 10));
                    }

                    // Safety: selesai cannot be before masuk
                    if ($tanggalSelesai->lt($tanggalMasuk)) {
                        $tanggalSelesai = $tanggalMasuk->copy()->addDays(1);
                    }


                    // Use diffInWeekdays to exclude weekends
                    $durasiHari = $tanggalMasuk->diffInWeekdays($tanggalSelesai);

                } else {
                    // PENDING: Should generally be recent (fresh items)
                    // Default: entered 0 to 5 days ago (so duration is 0-5 days)
                    $daysAgo = rand(0, 5);
                    $tanggalMasuk = Carbon::now()->subDays($daysAgo);
                    $tanggalTarget = $tanggalMasuk->copy()->addDays($targetHari);

                    // OVERDUE LOGIC: Force some pending items to be old/overdue
                    if ($i % 5 == 0) { // Every 5th item is overdue
                        // Use weekdays for overdue calculation to be precise? 
                        // Actually just setting the date is enough, duration calc handles it.
                        $overdueDays = rand(5, 30);
                        $tanggalMasuk = Carbon::now()->subDays($targetHari + $overdueDays);
                        $tanggalTarget = $tanggalMasuk->copy()->addDays($targetHari);
                    }

                    // Use diffInWeekdays for pending items too
                    $durasiHari = $tanggalMasuk->diffInWeekdays(Carbon::now());
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
                    'file_uploads' => [],
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
