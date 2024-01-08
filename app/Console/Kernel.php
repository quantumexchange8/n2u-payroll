<?php

namespace App\Console;

use App\Models\User;
use App\Models\PunchRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('punch-record-command')->dailyAt('06:00')->timezone('Asia/Kuala_Lumpur');

        $schedule->call(function () {
            // Retrieve users who have punch records with status_clock = 1
            $punchRecords = PunchRecord::all();
            $users = User::where('role', 'member')->get();

            foreach ($users as $user) {
                $userPunchRecords = $punchRecords->where('employee_id', $user->id)->sortBy('created_at');

                $combinedRecords = collect([]);
                $currentPair = collect([]);

                foreach ($userPunchRecords as $record) {
                    // Check if the current record is the start of a new pair
                    if ($record->status_clock % 2 == 1) {
                        $currentPair = collect([$record]);
                    } else {
                        // If it's an even status_clock, add it to the current pair
                        $currentPair->push($record);

                        // If it's the end of a pair, add the pair to the combined records
                        if ($record->status_clock % 2 == 0) {
                            $combinedRecords->push($currentPair);
                        }
                    }
                }

                // After the loop, handle single records
                foreach ($userPunchRecords as $record) {
                    $isCombined = $combinedRecords->flatten()->contains(function ($combinedRecord) use ($record) {
                        return $combinedRecord->id == $record->id;
                    });

                    if (!$isCombined) {
                        if ($record->status_clock == 1) {
                            $newRecord = new PunchRecord();
                            $newRecord->employee_id = $user->id;
                            $newRecord->out = 'Clock Out';
                            $newRecord->clock_out_time = now();
                            $newRecord->status = 'Notice';
                            $newRecord->status_clock = 2;
                            $newRecord->save();
                        } else {
                            $newRecord = new PunchRecord();
                            $newRecord->employee_id = $user->id;
                            $newRecord->out = 'Clock Out';
                            $newRecord->clock_out_time = now();
                            $newRecord->status = 'Notice';
                            $newRecord->status_clock = 4;
                            $newRecord->save();
                        }
                    }
                }
            }

        })->dailyAt('06:00')->timezone('Asia/Kuala_Lumpur');
        // dailyAt('06:00')->timezone('Asia/Kuala_Lumpur');

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
