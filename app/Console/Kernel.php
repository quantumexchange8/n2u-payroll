<?php

namespace App\Console;

use App\Models\User;
use App\Models\OtApproval;
use App\Models\PunchRecord;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
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

                // Handle combined records
                // foreach ($combinedRecords as $combinedPair) {
                //     foreach ($combinedPair as $record) {
                //         echo 'ID: ' . $record->id . ' User Name: ' . $user->full_name . ' - Combined Record: ' . $record->created_at . PHP_EOL;
                //     }
                // }

                // After the loop, handle single records
                foreach ($userPunchRecords as $record) {
                    $isCombined = $combinedRecords->flatten()->contains(function ($combinedRecord) use ($record) {
                        return $combinedRecord->id == $record->id;
                    });

                    if (!$isCombined) {

                        $date = $record->created_at->format('Y-m-d');
                        $employeeId = $record->employee_id;
                        $clockIn = $record->clock_in_time;

                        $currentDateTime = now();

                        $currentDate = now()->subDay()->toDateString();
                        $currentTimes = $currentDateTime->format('H:i:s');

                        $lateThreshold = Setting::where('setting_name', 'Late Threshold (in minutes)')->value('value');

                        $overtimeCalculation = Setting::where('setting_name', 'Overtime Calculation (in minutes)')->value('value');

                        $scheduleData = DB::table('schedules')
                                            ->join('users', 'schedules.employee_id', '=', 'users.id')
                                            ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')
                                            ->where('schedules.employee_id', $employeeId)
                                            ->where('schedules.date', $date)
                                            ->whereNull('schedules.deleted_at')
                                            ->orderBy('shifts.shift_start', 'asc')
                                            ->select('users.employee_id', 'shifts.shift_start', 'shifts.shift_end', 'schedules.date')
                                            ->get();

                        if ($scheduleData->isEmpty()) {

                            // echo '2ID: ' .$record->id. PHP_EOL;

                            $newRecord = new PunchRecord();
                            $newRecord->employee_id = $user->id;
                            $newRecord->out = 'Clock Out';
                            $newRecord->clock_out_time = now();
                            $newRecord->status = 'On-Time';

                            if ($record->status_clock == 1) {
                                $newRecord->status_clock = 2;
                            } else {
                                $newRecord->status_clock = 4;
                            }

                            $newRecord->total_work = null;
                            $newRecord->save();

                            $userId = $user->id;
                            $updateUser = User::find($userId);
                            $updateUser->update([
                                'status' => 1
                            ]);
                        } else {
                            $scheduleCount = count($scheduleData);
                            $firstShift = null;
                            $secondShift = null;

                            if ($scheduleCount > 0) {
                                // Access the shifts based on array index.
                                $firstShift = $scheduleData[0];

                                // Check if the user has two schedules.
                                if ($scheduleCount > 1) {
                                    $secondShift = $scheduleData[1];
                                }
                            }

                            if (!empty($firstShift)) {
                                $firstShiftStart = Carbon::now()->setTimeFromTimeString($firstShift->shift_start);
                                $firstShiftEnd = Carbon::now()->setTimeFromTimeString($firstShift->shift_end);

                                $checkFirstLate = $firstShiftStart->copy()->addMinutes($lateThreshold);
                                $checkFirstOT = $firstShiftEnd->copy()->addMinutes($overtimeCalculation);
                            }

                            if (!empty($secondShift)) {
                                $secondShiftStart = Carbon::now()->setTimeFromTimeString($secondShift->shift_start);
                                $secondShiftEnd = Carbon::now()->setTimeFromTimeString($secondShift->shift_end);

                                $checkSecondLate = $secondShiftStart->copy()->addMinutes($lateThreshold);
                                $checkSecondOT = $secondShiftEnd->copy()->addMinutes($overtimeCalculation);
                            }

                            if ($record->status_clock == 1) {

                                if ($firstShiftStart >= $firstShiftEnd) {
                                    $newFirstShiftStart = $firstShiftStart->subDay();
                                    $newCheckFirstLate = $checkFirstLate->subDay();

                                    if ($clockIn >= $newCheckFirstLate) {
                                        if ($currentDateTime >= $checkFirstOT) {
                                            $totalWork = $currentDateTime->diffInMinutes($clockIn);
                                        } else {
                                            $totalWork = $firstShiftEnd->diffInMinutes($clockIn);
                                        }

                                    } else {
                                        if ($currentDateTime >= $checkFirstOT) {
                                            $totalWork = $currentDateTime->diffInMinutes($newFirstShiftStart);
                                        } else {
                                            $totalWork = $firstShiftEnd->diffInMinutes($newFirstShiftStart);
                                        }
                                    }


                                } else {
                                    if ($clockIn >= $checkFirstLate) {
                                        if ($currentDateTime >= $checkFirstOT) {
                                            $totalWork = $currentDateTime->diffInMinutes($clockIn);
                                        } else {
                                            $totalWork = $firstShiftEnd->diffInMinutes($clockIn);
                                        }
                                    } else {
                                        if ($currentDateTime >= $checkFirstOT) {
                                            $totalWork = $currentDateTime->diffInMinutes($firstShiftStart);
                                        } else {
                                            $totalWork = $firstShiftEnd->diffInMinutes($firstShiftStart);
                                        }
                                    }
                                }

                            } else {

                                if ($secondShiftStart >= $secondShiftEnd) {
                                    $newSecondShiftStart = $secondShiftStart->subDay();
                                    $newCheckSecondLate = $checkSecondLate->subDay();

                                    if ($clockIn >= $newCheckSecondLate) {
                                        if ($currentDateTime >= $checkSecondOT) {
                                            $totalWork = $currentDateTime->diffInMinutes($clockIn);
                                        } else {
                                            $totalWork = $secondShiftEnd->diffInMinutes($clockIn);
                                        }

                                    } else {
                                        if ($currentDateTime >= $checkSecondOT) {
                                            $totalWork = $currentDateTime->diffInMinutes($newSecondShiftStart);
                                        } else {
                                            $totalWork = $secondShiftEnd->diffInMinutes($newSecondShiftStart);
                                        }
                                    }
                                } else {
                                    if ($clockIn >= $checkSecondLate) {
                                        if ($currentDateTime >= $checkSecondOT) {
                                            $totalWork = $currentDateTime->diffInMinutes($clockIn);
                                        } else {
                                            $totalWork = $secondShiftEnd->diffInMinutes($clockIn);
                                        }
                                    } else {
                                        if ($currentDateTime >= $checkSecondOT) {
                                            $totalWork = $currentDateTime->diffInMinutes($secondShiftStart);
                                        } else {
                                            $totalWork = $secondShiftEnd->diffInMinutes($secondShiftStart);
                                        }
                                    }
                                }

                            }

                            $totalWorkInHours = number_format($totalWork / 60, 2);

                            // echo '1ID: ' .$record->id. ' Time:' .$currentDate . ' User Name: ' . $newCheckFirstLate . ' - Single Record: ' . $date . PHP_EOL;

                            $newRecord = new PunchRecord();
                            $newRecord->employee_id = $user->id;
                            $newRecord->out = 'Clock Out';
                            $newRecord->clock_out_time = now();
                            $newRecord->status = 'On-Time';

                            if ($record->status_clock == 1) {
                                $newRecord->status_clock = 2;
                            } else {
                                $newRecord->status_clock = 4;
                            }

                            $newRecord->total_work = $totalWorkInHours;

                            if ($currentDateTime >= $checkFirstOT) {
                                $otMinutes = $currentDateTime->diffInMinutes($checkFirstOT);
                                $otInHours = number_format($otMinutes / 60, 2);

                                $newOt = OtApproval::create([
                                    'employee_id' => $record->employee_id,
                                    'date' => $currentDate,
                                    'shift_start' => $firstShift->shift_start,
                                    'shift_end' => $firstShift->shift_end,
                                    'clock_out_time' => $currentTimes,
                                    'ot_hour' => $otInHours,
                                    'status' => 'Pending'
                                ]);

                                $newRecord->ot_approval = 'Pending';
                            } elseif ($currentDateTime >= $checkSecondOT) {
                                // Handle the second type of OtApproval creation
                                $otMinutes = $currentDateTime->diffInMinutes($checkSecondOT);
                                $otInHours = number_format($otMinutes / 60, 2);

                                $newOt = OtApproval::create([
                                    'employee_id' => $record->employee_id,
                                    'date' => $currentDate,
                                    'shift_start' => $secondShift->shift_start,
                                    'shift_end' => $secondShift->shift_end,
                                    'clock_out_time' => $currentTimes,
                                    'ot_hour' => $otInHours,
                                    'status' => 'Pending'
                                ]);

                                $newRecord->ot_approval = 'Pending';
                            } else {
                                $newRecord->ot_approval = null;
                            }

                            $newRecord->save();

                            $userId = $user->id;
                            $updateUser = User::find($userId);
                            $updateUser->update([
                                'status' => 1
                            ]);

                            echo '1ID: ' .$record->id. ' Time:' .$currentDate . ' - Single Record: ' . $date . PHP_EOL;

                        }


                    }
                }
            }

        })->everyThirtySeconds();
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
