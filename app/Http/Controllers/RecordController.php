<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PunchRecord;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\Schedule;
use App\Models\User;
use App\Models\OtApproval;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class RecordController extends Controller
{

    public function getUserStatus($userId){
        $user = User::where('employee_id', $userId)->first();

        if ($user) {
            return response()->json([
                'status' => $user->status,
                'hashed_password' => $user->password,
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
    }
    public function comparePassword($userId, $userPassword){
        $user = User::where('employee_id', $userId)->first();

        if ($user) {
            $hashedPasswordFromServer = $user->password;

            $isPasswordCorrect = Hash::check($userPassword, $hashedPasswordFromServer);

            if ($isPasswordCorrect) {

                return response()->json(['status' => 'success']);
            } else {

                return response()->json(['status' => 'error', 'message' => 'Incorrect password'], 400);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
    }

    // Clock in at login page
    public function checkIn(Request $request) {

        // $userId = $request->input('employee_id');
        $userId = User::select('id')->where('employee_id', $request->input('userId'));
        $status = $request->input('status');
        $user = User::where('employee_id', $request->input('userId'))->first();
        // $user = User::where('employee_id', $userId)->get();
        $currentDate = now()->toDateString();
        $currentDate = Carbon::parse($currentDate);
        $currentDateTime = now();
        $currentTimes = $currentDateTime->format('H:i:s');

        $schedules = Schedule::with('shift_schedules')
        ->with('user')
        ->where('schedules.employee_id', $userId)
        ->whereDate('schedules.date', '=', $currentDate)
        ->where('off_day', 0)
        ->whereNull('schedules.deleted_at')
        ->get();


        $lateThreshold = Setting::where('setting_name', 'Late Threshold Minutes')->value('value');

        $overtimeCalculation = Setting::where('setting_name', 'Overtime Calculation')->value('value');

        if ($schedules) {

            $currentTime = now();

            $scheduleCount = $schedules->count();

            $firstShift = null;
            $secondShift = null;


            // Check if the user has at least one schedule.
            if ($scheduleCount > 0) {
                // Access the shifts based on array index.
                $firstShift = $schedules[0];

                // Check if the user has two schedules.
                if ($scheduleCount > 1) {
                    $secondShift = $schedules[1];
                }
            }

            if ($firstShift !== null) {
                $firstShiftDate = $firstShift->date;
            }

            if ($secondShift !== null) {
                $secondShiftDate = $secondShift->date;
            }

            // if(!empty($firstShift)){
            //     // $firstShiftStartTime = now()->setTimeFromTimeString($firstShift->shift_start);

            //     if ($firstShift->shift_start >= $firstShift->shift_end) {
            //         $firstShiftStartTime = now()->setTimeFromTimeString($firstShift->shift_start)->subDay();

            //         $firstShiftDate = Carbon::parse($firstShiftDate)->subDay();
            //         $firstShiftDate = $firstShiftDate->format('Y-m-d');

            //         $currentDate->subDay();
            //     } else {
            //         $firstShiftStartTime = now()->setTimeFromTimeString($firstShift->shift_start);
            //     }

            //     $firstShiftEndTime = now()->setTimeFromTimeString($firstShift->shift_end);
            // }

            // if(!empty($secondShift)){
            //     // $secondShiftStartTime = now()->setTimeFromTimeString($secondShift->shift_start);

            //     if ($secondShift->shift_start >= $secondShift->shift_end) {
            //         $secondShiftStartTime = now()->setTimeFromTimeString($secondShift->shift_start)->subDay();

            //         $secondShiftDate = Carbon::parse($secondShiftDate)->subDay();
            //         $secondShiftDate = $secondShiftDate->format('Y-m-d');

            //         $currentDate->subDay();
            //     } else {
            //         $secondShiftStartTime = now()->setTimeFromTimeString($secondShift->shift_start);
            //     }

            //     $secondShiftEndTime = now()->setTimeFromTimeString($secondShift->shift_end);
            // }

            if(!empty($firstShift)){
                $firstShiftStartTime = now()->setTimeFromTimeString($firstShift->shift_schedules->shift_start);

                if ($firstShift->shift_schedules->shift_start >= $firstShift->shift_schedules->shift_end) {
                    $firstShiftEndTime = now()->setTimeFromTimeString($firstShift->shift_schedules->shift_end)->addDay();

                    $firstShiftDate = Carbon::parse($firstShiftDate)->addDay();
                    $firstShiftDate = $firstShiftDate->format('Y-m-d');

                    $currentDate->addDay();
                } else {
                    $firstShiftEndTime = now()->setTimeFromTimeString($firstShift->shift_schedules->shift_end);
                }
            }

            if(!empty($secondShift)){
                $secondShiftStartTime = now()->setTimeFromTimeString($secondShift->shift_schedules->shift_start);

                if ($secondShift->shift_schedules->shift_start >= $secondShift->shift_schedules->shift_end) {
                    $secondShiftEndTime = now()->setTimeFromTimeString($secondShift->shift_schedules->shift_end)->addDay();

                    $secondShiftDate = Carbon::parse($secondShiftDate)->addDay();
                    $secondShiftDate = $secondShiftDate->format('Y-m-d');

                    $currentDate->addDay();
                } else {
                    $secondShiftEndTime = now()->setTimeFromTimeString($secondShift->shift_schedules->shift_end);
                }
            }

            // Count the number of clock-ins for the current date
            $clockinCount = PunchRecord::where('employee_id', $user->id)
                            ->whereDate('created_at', $currentDate)
                            ->where('in', 'Clock In')
                            ->count();

            // Count the number of clock-outs for the current date
            $clockoutCount = PunchRecord::where('employee_id', $user->id)
                            ->whereDate('created_at', $currentDate)
                            ->where('out', 'Clock Out')
                            ->count();


            if ($status === 'Clock In') {
                if ($clockinCount == 0 && $clockoutCount == 1){
                    // If clockinCount is 0 and clockoutCount is 1, reset clockoutCount to 0
                    $clockoutCount = 0;
                    $status_clock = $clockinCount + $clockoutCount + 1;
                } else {
                    $status_clock = $clockinCount + $clockoutCount + 1;
                }
            } elseif ($status === 'Clock Out') {
                if ($clockinCount == 1 && $clockoutCount == 1){
                    // If clockinCount is 1 and clockoutCount is 1, reset clockoutCount to 0
                    $clockoutCount = 0;
                    $status_clock = $clockinCount + $clockoutCount + 1;
                } else {
                    $status_clock = $clockinCount + $clockoutCount + 1;
                }
            }

            $recordData = [
                'employee_id' => $user->id,
                'in' => $status === 'Clock In' ? 'Clock In' : null,
                'out' => $status === 'Clock Out' ? 'Clock Out' : null,
                'clock_in_time' => $status === 'Clock In' ? now() : null,
                'clock_out_time' => $status === 'Clock Out' ? now() : null,
                'ot_approval' => null,
                'remarks' => null,
                'status_clock' => $status_clock,
            ];


            if(!empty($firstShift) && !empty($secondShift)){

                // Check if the user has reached the limit (2 times for both clock in and clock out)
                if ($status === 'Clock In' && $clockinCount >= 2) {
                    // Clock In limit reached, display error message
                    // return redirect()->route('homepage')->with('error', 'You have reached the limit of clock-in actions.');
                    return response()->json(['success' => false, 'error' => 'You have reached the limit of clock-in actions.']);
                }elseif ($status === 'Clock Out' && $clockoutCount >= 2) {
                    // Clock Out limit reached, display error message
                    // return redirect()->route('homepage')->with('error', 'You have reached the limit of clock-out actions.');
                    return response()->json(['success' => false, 'error' => 'You have reached the limit of clock-out actions.']);
                }

                if($status === 'Clock In') {
                    if ($clockinCount == 0){
                        if ($recordData['in'] === 'Clock In' && $currentTime->greaterThan($firstShiftStartTime)) {

                            // Calculate late threshold time
                            $lateThresholdTime = $firstShiftStartTime->copy()->addMinutes($lateThreshold);

                            if ($currentTime->greaterThan($lateThresholdTime)) {
                                $recordData['status'] = 'Late';
                            } else {
                                $recordData['status'] = 'On-Time';
                            }
                        } else {
                            $recordData['status'] = 'On-Time';
                        }

                        $recordData['clock_in_time'] = $currentDateTime;

                    }else if ($clockinCount == 1 ) {
                        if ($recordData['in'] === 'Clock In' && $currentTime->greaterThan($secondShiftStartTime)) {

                            // Calculate late threshold time
                            $lateThresholdTime = $secondShiftStartTime->copy()->addMinutes($lateThreshold);

                            if ($currentTime->greaterThan($lateThresholdTime)) {
                                $recordData['status'] = 'Late';
                            } else {
                                $recordData['status'] = 'On-Time';
                            }
                        } else {
                            $recordData['status'] = 'On-Time';
                        }

                        $recordData['clock_in_time'] = $currentDateTime;
                    }

                } elseif ($status === 'Clock Out') {
                    if ($clockoutCount == 0) {
                        if ($currentTime->lessThanOrEqualTo($firstShiftEndTime)) {
                            $recordData['status'] = 'On-Time';
                        } else {
                            // Get the "Overtime Calculation" setting value
                            $overtimeCalculationMinutes = intval($overtimeCalculation);

                            // Calculate the overtime threshold time
                            $overtimeThresholdTime = $firstShiftEndTime->copy()->addMinutes($overtimeCalculationMinutes);

                            if ($currentTime->greaterThan($overtimeThresholdTime)) {
                                $recordData['status'] = 'Overtime';
                                $recordData['ot_approval'] = 'Pending';

                                $clockout = $currentTimes;
                                $hourAndMinute = \Carbon\Carbon::parse($clockout)->format('H:i');

                                $clockout = $hourAndMinute;
                                $shiftEnd = $firstShift->shift_end;

                                $clockoutTime = \Carbon\Carbon::parse($clockout);
                                $shiftEndTime = \Carbon\Carbon::parse($shiftEnd);

                                $minutesDifference = $clockoutTime->diffInMinutes($shiftEndTime);

                                $totalHours = $minutesDifference / 60;

                                $totalHoursRounded = number_format($totalHours, 2);


                                $newot = OtApproval::create([
                                    'employee_id' => $user->id,
                                    'date' => $firstShiftDate,
                                    'shift_start' => $firstShift->shift_start,
                                    'shift_end' => $firstShift->shift_end,
                                    'clock_out_time' => $currentTimes,
                                    'ot_hour' => $totalHoursRounded,
                                    'status' => 'Pending'
                                ]);
                            } else {
                                $recordData['status'] = 'On-Time';
                            }
                        }

                        $recordData['clock_out_time'] = $currentDateTime;

                    }else if ($clockoutCount == 1) {

                        if ($currentTime->lessThanOrEqualTo($secondShiftEndTime)) {

                            $recordData['status'] = 'On-Time';

                        } else {

                            // Get the "Overtime Calculation" setting value
                            $overtimeCalculationMinutes = intval($overtimeCalculation);

                            // Calculate the overtime threshold time
                            $overtimeThresholdTime = $secondShiftEndTime->copy()->addMinutes($overtimeCalculationMinutes);

                            if ($currentTime->greaterThan($overtimeThresholdTime)) {

                                $recordData['status'] = 'Overtime';
                                $recordData['ot_approval'] = 'Pending';

                                $clockout = $currentTimes;
                                $hourAndMinute = \Carbon\Carbon::parse($clockout)->format('H:i');

                                $clockout = $hourAndMinute;
                                $shiftEnd = $secondShift->shift_end;

                                $clockoutTime = \Carbon\Carbon::parse($clockout);
                                $shiftEndTime = \Carbon\Carbon::parse($shiftEnd);

                                $minutesDifference = $clockoutTime->diffInMinutes($shiftEndTime);

                                $totalHours = $minutesDifference / 60;

                                $totalHoursRounded = number_format($totalHours, 2);

                                $newot = OtApproval::create([
                                    'employee_id' => $user->id,
                                    'date' => $secondShiftDate,
                                    'shift_start' => $secondShift->shift_start,
                                    'shift_end' => $secondShift->shift_end,
                                    'clock_out_time' => $currentTimes,
                                    'ot_hour' => $totalHoursRounded,
                                    'status' => 'Pending'
                                ]);
                            } else {
                                $recordData['status'] = 'On-Time';
                            }
                        }

                        $recordData['clock_out_time'] = $currentDateTime;
                    }
                }

                // Calculate total hours for the second shift
                if ($status === 'Clock Out' && $status_clock === 2) {

                    // Get the times for the first clock in, first clock out, last clock in, and last clock out
                    $firstClockInTime = PunchRecord::where('employee_id', $user->id)
                        ->whereDate('created_at', $currentDate)
                        ->where('in', 'Clock In')
                        ->orderBy('created_at', 'asc')
                        ->first()
                        ->created_at;

                    $firstClockOutTime = now();

                    $checkFirstLate = $firstShiftStartTime->copy()->addMinutes($lateThreshold);

                    $checkFirstOT = $firstShiftEndTime->copy()->addMinutes($overtimeCalculation);

                    // $checkSecondLate = $lastClockInTime->addMinutes($lateThreshold);

                    // $checkSecondOT = $lastClockOutTime->addMinutes($overtimeCalculation);

                    if ($firstClockInTime >= $checkFirstLate) {
                        if ($firstClockOutTime >= $checkFirstOT) {
                            $firstTotalWork = $firstShiftEndTime->diffInMinutes($firstClockInTime);
                        } else {
                            $firstTotalWork = $firstClockOutTime->diffInMinutes($firstClockInTime);
                        }
                    } else {
                        if ($firstClockOutTime >= $checkFirstOT) {
                            $firstTotalWork = $firstShiftEndTime->diffInMinutes($firstShiftStartTime);
                        } else {
                            $firstTotalWork = $firstClockOutTime->diffInMinutes($firstShiftStartTime);
                        }
                    }

                    $totalWorkInHours = number_format($firstTotalWork / 60, 2);
                    $recordData['total_work'] = $totalWorkInHours;
                }

                // Calculate total hours for the second shift
                if ($status === 'Clock Out' && $status_clock === 4) {

                    $lastClockInTime = PunchRecord::where('employee_id', $user->id)
                        ->whereDate('created_at', $currentDate)
                        ->where('in', 'Clock In')
                        ->orderBy('created_at', 'desc')
                        ->first()
                        ->created_at;

                    $lastClockOutTime = now();

                    $checkSecondLate = $secondShiftStartTime->copy()->addMinutes($lateThreshold);

                    $checkSecondOT = $secondShiftEndTime->copy()->addMinutes($overtimeCalculation);

                    if ($lastClockInTime >= $checkSecondLate) {
                        if ($lastClockOutTime >= $checkSecondOT) {
                            $secondTotalWork = $secondShiftEndTime->diffInMinutes($lastClockInTime);
                        }else {
                            $secondTotalWork = $lastClockOutTime->diffInMinutes($lastClockInTime);
                        }
                    }
                    else {
                        if ($lastClockOutTime >= $checkSecondOT) {
                            $secondTotalWork = $secondShiftEndTime->diffInMinutes($secondShiftStartTime);
                        }else {
                            $secondTotalWork = $lastClockOutTime->diffInMinutes($secondShiftStartTime);
                        }
                    }

                    $totalWorkInHours = number_format($secondTotalWork / 60, 2);
                    // dd($totalWorkInHours);
                    $recordData['total_work'] = $totalWorkInHours;
                }

            } else if (!empty($firstShift) && empty($secondShift)){

                // Check if the user has reached the limit (2 times for both clock in and clock out)
                if ($status === 'Clock In' && $clockinCount >= 1) {
                    // Clock In limit reached, display error message
                    // return redirect()->route('login')->with('error', 'You have reached the limit of clock-in actions.');
                    return response()->json(['success' => false, 'error' => 'You have reached the limit of clock-in actions.']);
                }
                elseif ($status === 'Clock Out' && $clockoutCount >= 1) {
                    // Clock Out limit reached, display error message
                    // return redirect()->route('login')->with('error', 'You have reached the limit of clock-out actions.');
                    return response()->json(['success' => false, 'error' => 'You have reached the limit of clock-out actions.']);
                }

                if($status === 'Clock In') {

                    if ($clockinCount == 0){
                        if ($recordData['in'] === 'Clock In' && $currentTime->greaterThan($firstShiftStartTime)) {
                            // Calculate late threshold time
                            $lateThresholdTime = $firstShiftStartTime->copy()->addMinutes($lateThreshold);

                            if ($currentTime->greaterThan($lateThresholdTime)) {
                                $recordData['status'] = 'Late';
                            } else {
                                $recordData['status'] = 'On-Time';
                            }
                        } else {
                            $recordData['status'] = 'On-Time';
                        }

                        $recordData['clock_in_time'] = $currentDateTime;

                    }

                } elseif ($status === 'Clock Out') {
                    if ($clockoutCount == 0) {

                        if ($currentTime->lessThanOrEqualTo($firstShiftEndTime)) {
                            $recordData['status'] = 'On-Time';

                        } else if($currentTime >= $firstShiftEndTime){
                            // Get the "Overtime Calculation" setting value
                            $overtimeCalculationMinutes = intval($overtimeCalculation);

                            // Calculate the overtime threshold time
                            $overtimeThresholdTime = $firstShiftEndTime->copy()->addMinutes($overtimeCalculationMinutes);

                            if ($currentTime->greaterThan($overtimeThresholdTime)) {
                                $recordData['status'] = 'Overtime';
                                $recordData['ot_approval'] = 'Pending';

                                $clockout = $currentTimes;
                                $hourAndMinute = \Carbon\Carbon::parse($clockout)->format('H:i');

                                $clockout = $hourAndMinute;
                                $shiftEnd = $firstShift->shift_end;

                                $clockoutTime = \Carbon\Carbon::parse($clockout);
                                $shiftEndTime = \Carbon\Carbon::parse($shiftEnd);

                                $minutesDifference = $clockoutTime->diffInMinutes($shiftEndTime);

                                $totalHours = $minutesDifference / 60;

                                $totalHoursRounded = number_format($totalHours, 2);

                                $newot = OtApproval::create([
                                    'employee_id' => $user->id,
                                    'date' => $firstShiftDate,
                                    'shift_start' => $firstShift->shift_start,
                                    'shift_end' => $firstShift->shift_end,
                                    'clock_out_time' => $currentTimes,
                                    'ot_hour' => $totalHoursRounded,
                                    'status' => 'Pending'
                                ]);

                            } else {
                                $recordData['status'] = 'On-Time';
                            }
                        }

                        $recordData['clock_out_time'] = $currentDateTime;

                    }
                }

                // Calculate total hours for the last clock out
                if ($status == 'Clock Out' && $status_clock === 2) {
                    $firstClockInTime = PunchRecord::where('employee_id', $user->id)
                        ->whereDate('created_at', $currentDate)
                        ->where('in', 'Clock In')
                        ->orderBy('created_at', 'asc')
                        ->first()
                        ->created_at;

                    $lastClockOutTime = now();

                    $checkLate = $firstShiftStartTime->copy()->addMinutes($lateThreshold);

                    $checkOT = $firstShiftEndTime->copy()->addMinutes($overtimeCalculation);

                    if ($firstClockInTime >= $checkLate) {

                        if ($lastClockOutTime >= $checkOT) {
                            $totalWork = $firstShiftEndTime->diffInMinutes($firstClockInTime);
                        } else{
                            $totalWork = $lastClockOutTime->diffInMinutes($firstClockInTime);
                        }
                    } else {
                        if ($lastClockOutTime >= $checkOT) {
                            $totalWork = $firstShiftEndTime->diffInMinutes($firstShiftStartTime);
                        } else {
                            $totalWork = $lastClockOutTime->diffInMinutes($firstShiftStartTime);
                        }
                    }

                    $totalWorkInHours = number_format($totalWork / 60, 2);
                    $recordData['total_work'] = $totalWorkInHours;
                }

            }

            $record = PunchRecord::create($recordData);

            if ($status === 'Clock In') {
                $user->status = 2;
                $user->save();
            } elseif ($status === 'Clock Out') {
                $user->status = 1;
                $user->save();
            }

            // return redirect()->route('login');
            return response()->json(['success' => true]);
        } else {

            // Schedule information not found, insert "Clock In" and "Clock Out" records with null values
            $recordData = [
                'employee_id' => $user->id,
                'in' => $status === 'Clock In' ? 'Clock In' : null,
                'out' => $status === 'Clock Out' ? 'Clock Out' : null,
                'clock_in_time' => null,
                'clock_out_time' => null,
                'ot_approval' => null,
                'remarks' => null,
                'status_clock' => 1
            ];

            // $record = PunchRecord::create($recordData);

            return redirect()->route('login')->with('error', 'Schedule information not found.');
        }
    }

    // Clock in at user's homepage
    public function clock_in(Request $request) {

        $user = Auth::user();

        // Determine whether the user is clocking in or out based on the button text.
        // Assuming 'status' corresponds to the button text
        $status = $request->input('status');

        $currentDate = now()->toDateString();
        $currentDate = Carbon::parse($currentDate);
        $currentDateTime = now();
        $currentTimes = $currentDateTime->format('H:i:s');

        // $schedules = DB::table('schedules')
        $schedules = Schedule::with('shift_schedules')
            // ->join('users', 'schedules.employee_id', '=', 'users.id')
            // ->join('shift_schedules', 'shift_schedules.shift_id', '=', 'shift_schedules.id')
            // ->with('shift_schedules')
            ->with('user')
            // ->select('schedules.id', 'schedules.employee_id', 'user.full_name', 'shift_schedules.shift_start', 'shift_schedules.shift_end', 'schedules.date')
            // ->select('schedules.id', 'schedules.employee_id', 'shift_schedules.shift_start', 'shift_schedules.shift_end', 'schedules.date')
            ->where('schedules.employee_id', $user->id)
            ->whereDate('schedules.date', '=', $currentDate)
            ->where('off_day', 0)
            ->whereNull('schedules.deleted_at')
            // ->orderBy('shift_schedules.shift_start', 'asc')
            ->get();

        $lateThreshold = Setting::where('setting_name', 'Late Threshold Minutes')->value('value');
        
        $overtimeCalculation = Setting::where('setting_name', 'Overtime Calculation')->value('value');

        if ($schedules) {
            $currentTime = now();

            $scheduleCount = $schedules->count();

            $firstShift = null;
            $secondShift = null;

            // Check if the user has at least one schedule.
            if ($scheduleCount > 0) {
                // Access the shifts based on array index.
                $firstShift = $schedules[0];

                // Check if the user has two schedules.
                if ($scheduleCount > 1) {
                    $secondShift = $schedules[1];
                }
            }

            if ($firstShift !== null) {
                $firstShiftDate = $firstShift->date;
            }

            if ($secondShift !== null) {
                $secondShiftDate = $secondShift->date;
            }


            if(!empty($firstShift)){
                $firstShiftStartTime = now()->setTimeFromTimeString($firstShift->shift_schedules->shift_start);

                if ($firstShift->shift_schedules->shift_start >= $firstShift->shift_schedules->shift_end) {
                    $firstShiftEndTime = now()->setTimeFromTimeString($firstShift->shift_schedules->shift_end)->addDay();

                    $firstShiftDate = Carbon::parse($firstShiftDate)->addDay();
                    $firstShiftDate = $firstShiftDate->format('Y-m-d');

                    $currentDate->addDay();
                } else {
                    $firstShiftEndTime = now()->setTimeFromTimeString($firstShift->shift_schedules->shift_end);
                }
            }

            if(!empty($secondShift)){
                $secondShiftStartTime = now()->setTimeFromTimeString($secondShift->shift_schedules->shift_start);

                if ($secondShift->shift_schedules->shift_start >= $secondShift->shift_schedules->shift_end) {
                    $secondShiftEndTime = now()->setTimeFromTimeString($secondShift->shift_schedules->shift_end)->addDay();

                    $secondShiftDate = Carbon::parse($secondShiftDate)->addDay();
                    $secondShiftDate = $secondShiftDate->format('Y-m-d');

                    $currentDate->addDay();
                } else {
                    $secondShiftEndTime = now()->setTimeFromTimeString($secondShift->shift_schedules->shift_end);
                }
            }

            // Count the number of clock-ins for the current date
            $clockinCount = PunchRecord::where('employee_id', $user->id)
                            ->whereDate('created_at', $currentDate)
                            ->where('in', 'Clock In')
                            ->count();

            // Count the number of clock-outs for the current date
            $clockoutCount = PunchRecord::where('employee_id', $user->id)
                            ->whereDate('created_at', $currentDate)
                            ->where('out', 'Clock Out')
                            ->count();

            if ($status === 'Clock In') {
                if ($clockinCount == 0 && $clockoutCount == 1){
                    // If clockinCount is 0 and clockoutCount is 1, reset clockoutCount to 0
                    $clockoutCount = 0;
                    $status_clock = $clockinCount + $clockoutCount + 1;
                } else {
                    $status_clock = $clockinCount + $clockoutCount + 1;
                }
            } elseif ($status === 'Clock Out') {
                if ($clockinCount == 1 && $clockoutCount == 1){
                    // If clockinCount is 1 and clockoutCount is 1, reset clockoutCount to 0
                    $clockoutCount = 0;
                    $status_clock = $clockinCount + $clockoutCount + 1;
                } else {
                    $status_clock = $clockinCount + $clockoutCount + 1;
                }
            }


            $recordData = [
                'employee_id' => $user->id,
                'in' => $status === 'Clock In' ? 'Clock In' : null,
                'out' => $status === 'Clock Out' ? 'Clock Out' : null,
                'clock_in_time' => $status === 'Clock In' ? now() : null,
                'clock_out_time' => $status === 'Clock Out' ? now() : null,
                'ot_approval' => null,
                'remarks' => null,
                'status_clock' => $status_clock,
            ];

            if(!empty($firstShift) && !empty($secondShift)){

                // Check if the user has reached the limit (2 times for both clock in and clock out)
                if ($status === 'Clock In' && $clockinCount >= 2) {
                    // Clock In limit reached, display error message
                    // return redirect()->route('homepage')->with('error', 'You have reached the limit of clock-in actions.');
                    return response()->json(['success' => false, 'error' => 'You have reached the limit of clock-in actions.']);
                } elseif ($status === 'Clock Out' && $clockoutCount >= 2) {
                    // Clock Out limit reached, display error message
                    // return redirect()->route('homepage')->with('error', 'You have reached the limit of clock-out actions.');
                    return response()->json(['success' => false, 'error' => 'You have reached the limit of clock-out actions.']);
                }

                if($status === 'Clock In') {
                    if ($clockinCount == 0){
                        if ($recordData['in'] === 'Clock In' && $currentTime->greaterThan($firstShiftStartTime)) {

                            // Calculate late threshold time
                            $lateThresholdTime = $firstShiftStartTime->copy()->addMinutes($lateThreshold);

                            if ($currentTime->greaterThan($lateThresholdTime)) {
                                $recordData['status'] = 'Late';
                            } else {
                                $recordData['status'] = 'On-Time';
                            }
                        } else {
                            $recordData['status'] = 'On-Time';
                        }

                        $recordData['clock_in_time'] = $currentDateTime;

                    }else if ($clockinCount == 1 ) {
                        if ($recordData['in'] === 'Clock In' && $currentTime->greaterThan($secondShiftStartTime)) {

                            // Calculate late threshold time
                            $lateThresholdTime = $secondShiftStartTime->copy()->addMinutes($lateThreshold);

                            if ($currentTime->greaterThan($lateThresholdTime)) {
                                $recordData['status'] = 'Late';
                            } else {
                                $recordData['status'] = 'On-Time';
                            }
                        } else {
                            $recordData['status'] = 'On-Time';
                        }

                        $recordData['clock_in_time'] = $currentDateTime;
                    }

                } elseif ($status === 'Clock Out') {
                    if ($clockoutCount == 0) {
                        if ($currentTime->lessThanOrEqualTo($firstShiftEndTime)) {
                            $recordData['status'] = 'On-Time';
                        } else {
                            // Get the "Overtime Calculation" setting value
                            $overtimeCalculationMinutes = intval($overtimeCalculation);

                            // Calculate the overtime threshold time
                            $overtimeThresholdTime = $firstShiftEndTime->copy()->addMinutes($overtimeCalculationMinutes);

                            // dd($overtimeCalculationMinutes, $overtimeThresholdTime);

                            if ($currentTime->greaterThan($overtimeThresholdTime)) {
                                $recordData['status'] = 'Overtime';
                                $recordData['ot_approval'] = 'Pending';

                                // $tt_ot = $currentTimes - $schedule->shift_end;
                                $clockout = $currentTimes;
                                $hourAndMinute = \Carbon\Carbon::parse($clockout)->format('H:i');

                                $clockout = $hourAndMinute;
                                $shiftEnd = $firstShift->shift_end;

                                $clockoutTime = \Carbon\Carbon::parse($clockout);
                                $shiftEndTime = \Carbon\Carbon::parse($shiftEnd);

                                $minutesDifference = $clockoutTime->diffInMinutes($shiftEndTime);

                                // You can also get the hours and minutes separately if needed
                                // $hours = floor($minutesDifference / 60);
                                $totalHours = $minutesDifference / 60;

                                $totalHoursRounded = number_format($totalHours, 2);

                                $newot = OtApproval::create([
                                    'employee_id' => Auth::user()->id,
                                    'date' => $firstShiftDate,
                                    'shift_start' => $firstShift->shift_start,
                                    'shift_end' => $firstShift->shift_end,
                                    'clock_out_time' => $currentTimes,
                                    'ot_hour' => $totalHoursRounded,
                                    'status' => 'Pending'
                                ]);
                            } else {
                                $recordData['status'] = 'On-Time';
                            }
                        }

                        $recordData['clock_out_time'] = $currentDateTime;

                    }else if ($clockoutCount == 1) {
                        if ($currentTime->lessThanOrEqualTo($secondShiftEndTime)) {
                            $recordData['status'] = 'On-Time';
                        } else {
                            // Get the "Overtime Calculation" setting value
                            $overtimeCalculationMinutes = intval($overtimeCalculation);

                            // Calculate the overtime threshold time
                            $overtimeThresholdTime = $secondShiftEndTime->copy()->addMinutes($overtimeCalculationMinutes);

                            if ($currentTime->greaterThan($overtimeThresholdTime)) {
                                $recordData['status'] = 'Overtime';
                                $recordData['ot_approval'] = 'Pending';

                                // $tt_ot = $currentTimes - $schedule->shift_end;
                                $clockout = $currentTimes;
                                $hourAndMinute = \Carbon\Carbon::parse($clockout)->format('H:i');

                                $clockout = $hourAndMinute;
                                $shiftEnd = $secondShift->shift_end;

                                $clockoutTime = \Carbon\Carbon::parse($clockout);
                                $shiftEndTime = \Carbon\Carbon::parse($shiftEnd);

                                $minutesDifference = $clockoutTime->diffInMinutes($shiftEndTime);

                                $totalHours = $minutesDifference / 60;

                                $totalHoursRounded = number_format($totalHours, 2);

                                $newot = OtApproval::create([
                                    'employee_id' => Auth::user()->id,
                                    'date' => $secondShiftDate,
                                    'shift_start' => $secondShift->shift_start,
                                    'shift_end' => $secondShift->shift_end,
                                    'clock_out_time' => $currentTimes,
                                    'ot_hour' => $totalHoursRounded,
                                    'status' => 'Pending'
                                ]);
                            } else {
                                $recordData['status'] = 'On-Time';
                            }
                        }
                        $recordData['clock_out_time'] = $currentDateTime;
                    }
                }

                // Calculate total hours for the second shift
                if ($status === 'Clock Out' && $status_clock === 2) {

                    // Get the times for the first clock in, first clock out, last clock in, and last clock out
                    $firstClockInTime = PunchRecord::where('employee_id', $user->id)
                        ->whereDate('created_at', $currentDate)
                        ->where('in', 'Clock In')
                        ->orderBy('created_at', 'asc')
                        ->first()
                        ->created_at;

                    // $firstClockOutTime = PunchRecord::where('employee_id', $user->id)
                    //     ->whereDate('created_at', $currentDate)
                    //     ->where('out', 'Clock Out')
                    //     ->orderBy('created_at', 'asc')
                    //     ->first()
                    //     ->created_at;

                    $firstClockOutTime = now();

                    // $lastClockInTime = PunchRecord::where('employee_id', $user->id)
                    //     ->whereDate('created_at', $currentDate)
                    //     ->where('in', 'Clock In')
                    //     ->orderBy('created_at', 'desc')
                    //     ->first()
                    //     ->created_at;

                    // $lastClockOutTime = now();

                    $checkFirstLate = $firstShiftStartTime->copy()->addMinutes($lateThreshold);

                    $checkFirstOT = $firstShiftEndTime->copy()->addMinutes($overtimeCalculation);

                    // $checkSecondLate = $lastClockInTime->addMinutes($lateThreshold);

                    // $checkSecondOT = $lastClockOutTime->addMinutes($overtimeCalculation);

                    if ($firstClockInTime >= $checkFirstLate) {
                        if ($firstClockOutTime >= $checkFirstOT) {
                            $firstTotalWork = $firstShiftEndTime->diffInMinutes($firstClockInTime);
                        } else {
                            $firstTotalWork = $firstClockOutTime->diffInMinutes($firstClockInTime);
                        }
                    } else {
                        if ($firstClockOutTime >= $checkFirstOT) {
                            $firstTotalWork = $firstShiftEndTime->diffInMinutes($firstShiftStartTime);
                        } else {
                            $firstTotalWork = $firstClockOutTime->diffInMinutes($firstShiftStartTime);
                        }
                    }

                    $totalWorkInHours = number_format($firstTotalWork / 60, 2);
                    $recordData['total_work'] = $totalWorkInHours;
                }

                // Calculate total hours for the second shift
                if ($status === 'Clock Out' && $status_clock === 4) {
                    // Get the times for the first clock in, first clock out, last clock in, and last clock out
                    // $firstClockInTime = PunchRecord::where('employee_id', $user->id)
                    //     ->whereDate('created_at', $currentDate)
                    //     ->where('in', 'Clock In')
                    //     ->orderBy('created_at', 'asc')
                    //     ->first()
                    //     ->created_at;

                    // $firstClockOutTime = PunchRecord::where('employee_id', $user->id)
                    //     ->whereDate('created_at', $currentDate)
                    //     ->where('out', 'Clock Out')
                    //     ->orderBy('created_at', 'asc')
                    //     ->first()
                    //     ->created_at;

                    $lastClockInTime = PunchRecord::where('employee_id', $user->id)
                        ->whereDate('created_at', $currentDate)
                        ->where('in', 'Clock In')
                        ->orderBy('created_at', 'desc')
                        ->first()
                        ->created_at;

                    $lastClockOutTime = now();

                    // $checkFirstLate = $firstShiftStartTime->copy()->addMinutes($lateThreshold);

                    // $checkFirstOT = $firstShiftEndTime->copy()->addMinutes($overtimeCalculation);

                    $checkSecondLate = $secondShiftStartTime->copy()->addMinutes($lateThreshold);

                    $checkSecondOT = $secondShiftEndTime->copy()->addMinutes($overtimeCalculation);

                    // if ($firstClockInTime >= $firstShiftStartTime && $firstClockInTime >= $checkFirstLate) {
                    //     if ($firstClockOutTime >= $firstShiftEndTime && $firstClockOutTime >= $checkFirstOT) {
                    //         $firstTotalWork = $firstShiftEndTime->diffInMinutes($firstClockInTime);
                    //     } else if ($firstClockOutTime >= $firstShiftEndTime && $firstClockOutTime <= $checkFirstOT) {
                    //         $firstTotalWork = $firstClockOutTime->diffInMinutes($firstClockInTime);
                    //     } else if ($firstClockOutTime <= $firstShiftEndTime) {
                    //         $firstTotalWork = $firstClockOutTime->diffInMinutes($firstClockInTime);
                    //     }
                    // } else {
                    //     if ($firstClockOutTime >= $firstShiftEndTime && $firstClockOutTime >= $checkFirstOT) {
                    //         $firstTotalWork = $firstShiftEndTime->diffInMinutes($firstShiftStartTime);
                    //     } else if ($firstClockOutTime >= $firstShiftEndTime && $firstClockOutTime <= $checkFirstOT) {
                    //         $firstTotalWork = $firstClockOutTime->diffInMinutes($firstShiftStartTime);
                    //     } else if ($firstClockOutTime <= $firstShiftEndTime) {
                    //         $firstTotalWork = $firstClockOutTime->diffInMinutes($firstShiftStartTime);
                    //     }
                    // }

                    if ($lastClockInTime >= $checkSecondLate) {
                        if ($lastClockOutTime >= $checkSecondOT) {
                            $secondTotalWork = $secondShiftEndTime->diffInMinutes($lastClockInTime);
                        }else {
                            $secondTotalWork = $lastClockOutTime->diffInMinutes($lastClockInTime);
                        }
                    }
                    else {
                        if ($lastClockOutTime >= $checkSecondOT) {
                            $secondTotalWork = $secondShiftEndTime->diffInMinutes($secondShiftStartTime);
                        }else {
                            $secondTotalWork = $lastClockOutTime->diffInMinutes($secondShiftStartTime);
                        }
                    }


                    $totalWorkInHours = number_format($secondTotalWork / 60, 2);
                    $recordData['total_work'] = $totalWorkInHours;
                }

            } else if (!empty($firstShift) && empty($secondShift)){

                // Check if the user has reached the limit (2 times for both clock in and clock out)
                if ($status === 'Clock In' && $clockinCount >= 1) {
                    // Clock In limit reached, display error message
                    // return redirect()->route('homepage')->with('error', 'You have reached the limit of clock-in actions.');
                    return response()->json(['success' => false, 'error' => 'You have reached the limit of clock-in actions.']);
                } elseif ($status === 'Clock Out' && $clockoutCount >= 1) {
                    // Clock Out limit reached, display error message
                    // return redirect()->route('homepage')->with('error', 'You have reached the limit of clock-out actions.');
                    return response()->json(['success' => false, 'error' => 'You have reached the limit of clock-out actions.']);
                }

                if($status === 'Clock In') {

                    if ($clockinCount == 0){
                        if ($recordData['in'] === 'Clock In' && $currentTime->greaterThan($firstShiftStartTime)) {
                            // Calculate late threshold time
                            $lateThresholdTime = $firstShiftStartTime->copy()->addMinutes($lateThreshold);

                            if ($currentTime->greaterThan($lateThresholdTime)) {
                                $recordData['status'] = 'Late';
                            } else {
                                $recordData['status'] = 'On-Time';
                            }
                        } else {
                            $recordData['status'] = 'On-Time';
                        }

                        $recordData['clock_in_time'] = $currentDateTime;
                    }

                } elseif ($status === 'Clock Out') {

                    if ($clockoutCount == 0) {

                        if ($currentTime->lessThanOrEqualTo($firstShiftEndTime)) {
                            $recordData['status'] = 'On-Time';

                        } else if($currentTime >= $firstShiftEndTime){
                            // Get the "Overtime Calculation" setting value
                            $overtimeCalculationMinutes = intval($overtimeCalculation);

                            // Calculate the overtime threshold time
                            $overtimeThresholdTime = $firstShiftEndTime->copy()->addMinutes($overtimeCalculationMinutes);

                            if ($currentTime->greaterThan($overtimeThresholdTime)) {
                                $recordData['status'] = 'Overtime';
                                $recordData['ot_approval'] = 'Pending';

                                $clockout = $currentTimes;
                                $hourAndMinute = \Carbon\Carbon::parse($clockout)->format('H:i');

                                $clockout = $hourAndMinute;
                                $shiftEnd = $firstShift->shift_end;

                                $clockoutTime = \Carbon\Carbon::parse($clockout);
                                $shiftEndTime = \Carbon\Carbon::parse($shiftEnd);

                                $minutesDifference = $clockoutTime->diffInMinutes($shiftEndTime);

                                $totalHours = $minutesDifference / 60;

                                $totalHoursRounded = number_format($totalHours, 2);

                                $newot = OtApproval::create([
                                    'employee_id' => Auth::user()->id,
                                    'date' => $firstShiftDate,
                                    'shift_start' => $firstShift->shift_start,
                                    'shift_end' => $firstShift->shift_end,
                                    'clock_out_time' => $currentTimes,
                                    'ot_hour' => $totalHoursRounded,
                                    'status' => 'Pending'
                                ]);
                            } else {
                                $recordData['status'] = 'On-Time';
                            }
                        }

                        $recordData['clock_out_time'] = $currentDateTime;
                    }
                }

                // Calculate total hours for the last clock out
                if ($status === 'Clock Out' && $status_clock === 2) {

                    $firstClockInTime = PunchRecord::where('employee_id', $user->id)
                        ->whereDate('created_at', $currentDate)
                        ->where('in', 'Clock In')
                        ->orderBy('created_at', 'asc')
                        ->first()
                        ->created_at;

                    $lastClockOutTime = now();

                    $checkLate = $firstShiftStartTime->copy()->addMinutes($lateThreshold);

                    $checkOT = $firstShiftEndTime->copy()->addMinutes($overtimeCalculation);

                    if ($firstClockInTime >= $checkLate) {

                        if ($lastClockOutTime >= $checkOT) {
                            $totalWork = $firstShiftEndTime->diffInMinutes($firstClockInTime);
                        } else{
                            $totalWork = $lastClockOutTime->diffInMinutes($firstClockInTime);
                        }
                    } else {
                        if ($lastClockOutTime >= $checkOT) {
                            $totalWork = $firstShiftEndTime->diffInMinutes($firstShiftStartTime);
                        } else {
                            $totalWork = $lastClockOutTime->diffInMinutes($firstShiftStartTime);
                        }
                    }

                    $totalWorkInHours = number_format($totalWork / 60, 2);
                    $recordData['total_work'] = $totalWorkInHours;
                }

            }

            if ($status === 'Clock In') {
                $user->status = 2;
                $user->save();
            } elseif ($status === 'Clock Out') {
                $user->status = 1;
                $user->save();
            }

            $record = PunchRecord::create($recordData);

            // return redirect()->route('homepage')->with('success');
            return response()->json(['success' => true]);
        }
        else {

            // Schedule information not found, insert "Clock In" and "Clock Out" records with null values
            $recordData = [
                'employee_id' => $user->id,
                'in' => $status === 'Clock In' ? 'Clock In' : null,
                'out' => $status === 'Clock Out' ? 'Clock Out' : null,
                'clock_in_time' => null,
                'clock_out_time' => null,
                'ot_approval' => null,
                'remarks' => null,
                'status_clock' => 1
            ];

            $record = PunchRecord::create($recordData);

            return redirect()->route('homepage')->with('error', 'Schedule information not found.');
        }
    }
}
