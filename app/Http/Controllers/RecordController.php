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

        $userId = $request->input('userId');
        $status = $request->input('status');
        $user = User::where('employee_id', $userId)->first();

        $currentDate = now()->toDateString();
        $currentDateTime = now();
        $currentTimes = $currentDateTime->format('H:i:s');

        $schedules = DB::table('schedules')
            ->join('users', 'schedules.employee_id', '=', 'users.id')
            ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')
            ->select('schedules.id', 'schedules.employee_id', 'users.full_name', 'shifts.shift_start', 'shifts.shift_end', 'schedules.date')
            ->where('schedules.employee_id', $user->id)
            ->whereDate('schedules.date', '=', $currentDate)
            ->whereNull('schedules.deleted_at')
            ->orderBy('shifts.shift_start', 'asc')
            ->get();

        $lateThreshold = Setting::where('setting_name', 'Late Threshold (in minutes)')->value('value');

        $overtimeCalculation = Setting::where('setting_name', 'Overtime Calculation (in minutes)')->value('value');

        if ($status === 'Clock In') {
            $user->status = 2;
            $user->save();
        } elseif ($status === 'Clock Out') {
            $user->status = 1;
            $user->save();
        }

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

            if(!empty($firstShift)){
                $firstShiftStartTime = now()->setTimeFromTimeString($firstShift->shift_start);
                $firstShiftEndTime = now()->setTimeFromTimeString($firstShift->shift_end);
            }

            if(!empty($secondShift)){
                $secondShiftStartTime = now()->setTimeFromTimeString($secondShift->shift_start);
                $secondShiftEndTime = now()->setTimeFromTimeString($secondShift->shift_end);
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
                $status_clock = $clockinCount + $clockoutCount + 1;
            } elseif ($status === 'Clock Out') {
                $status_clock = $clockinCount + $clockoutCount + 1;
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

                                dd($user->id);

                                $newot = OtApproval::create([
                                    'employee_id' => $user->id,
                                    'date' => $firstShift->date,
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
                                    'date' => $secondShift->date,
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

                    if ($firstClockInTime >= $firstShiftStartTime && $firstClockInTime >= $checkFirstLate) {
                        if ($firstClockOutTime >= $firstShiftEndTime && $firstClockOutTime >= $checkFirstOT) {
                            $firstTotalWork = $firstShiftEndTime->diffInMinutes($firstClockInTime);
                        } else {
                            $firstTotalWork = $firstClockOutTime->diffInMinutes($firstClockInTime);
                        }
                    } else {
                        if ($firstClockOutTime >= $firstShiftEndTime && $firstClockOutTime >= $checkFirstOT) {
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

                    if ($lastClockInTime >= $secondShiftStartTime && $lastClockInTime >= $checkSecondLate) {
                        if ($lastClockOutTime >= $secondShiftEndTime && $lastClockOutTime >= $checkSecondOT) {
                            $secondTotalWork = $secondShiftEndTime->diffInMinutes($lastClockInTime);
                        }else {
                            $secondTotalWork = $lastClockOutTime->diffInMinutes($lastClockInTime);
                        }
                    }
                    else {
                        if ($lastClockOutTime >= $secondShiftEndTime && $lastClockOutTime >= $checkSecondOT) {
                            $secondTotalWork = $secondShiftEndTime->diffInMinutes($secondShiftStartTime);
                        }else {
                            $secondTotalWork = $lastClockOutTime->diffInMinutes($secondShiftStartTime);
                        }
                    }

                    $totalWorkInHours = number_format($secondTotalWork / 60, 2);
                    $recordData['total_work'] = $totalWorkInHours;
                }

            } else if (!empty($firstShift) && empty($secondShift)){

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
                                    'date' => $firstShift->date,
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
                    if ($firstClockInTime >= $firstShiftStartTime && $firstClockInTime >= $checkLate) {
                        if ($lastClockOutTime >= $firstShiftEndTime && $lastClockOutTime >= $checkOT) {
                            $totalWork = $firstShiftEndTime->diffInMinutes($firstClockInTime);
                        } else{
                            $totalWork = $lastClockOutTime->diffInMinutes($firstClockInTime);
                        }
                    } else {
                        if ($lastClockOutTime >= $firstShiftEndTime && $lastClockOutTime >= $checkOT) {
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

            return redirect()->route('homepage');
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

            $record = PunchRecord::create($recordData);

            return redirect()->route('homepage')->with('error', 'Schedule information not found.');
        }
    }

    // Clock in at user's homepage
    public function clock_in(Request $request) {

        $user = Auth::user();

        // Determine whether the user is clocking in or out based on the button text.
        // Assuming 'status' corresponds to the button text
        $status = $request->input('status');

        $currentDate = now()->toDateString();
        $currentDateTime = now();
        $currentTimes = $currentDateTime->format('H:i:s');

        $schedules = DB::table('schedules')
            ->join('users', 'schedules.employee_id', '=', 'users.id')
            ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')
            ->select('schedules.id', 'schedules.employee_id', 'users.full_name', 'shifts.shift_start', 'shifts.shift_end', 'schedules.date')
            ->where('schedules.employee_id', $user->id)
            ->whereDate('schedules.date', '=', $currentDate)
            ->whereNull('schedules.deleted_at')
            ->orderBy('shifts.shift_start', 'asc')
            ->get();

        $lateThreshold = Setting::where('setting_name', 'Late Threshold (in minutes)')->value('value');

        $overtimeCalculation = Setting::where('setting_name', 'Overtime Calculation (in minutes)')->value('value');

        if ($status === 'Clock In') {
            $user->status = 2;
            $user->save();
        } elseif ($status === 'Clock Out') {
            $user->status = 1;
            $user->save();
        }

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

            if(!empty($firstShift)){
                $firstShiftStartTime = now()->setTimeFromTimeString($firstShift->shift_start);
                $firstShiftEndTime = now()->setTimeFromTimeString($firstShift->shift_end);
            }

            if(!empty($secondShift)){
                $secondShiftStartTime = now()->setTimeFromTimeString($secondShift->shift_start);
                $secondShiftEndTime = now()->setTimeFromTimeString($secondShift->shift_end);
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
                $status_clock = $clockinCount + $clockoutCount + 1;
            } elseif ($status === 'Clock Out') {
                $status_clock = $clockinCount + $clockoutCount + 1;
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

                // Check if the user has reached the limit (4 times for both clock in and clock out)
                // if ($clockinCount + $clockoutCount >= 4) {
                //     // Limit reached, disable the button
                //     return redirect()->route('homepage')->with('error', 'You have reached the limit of clock-in and clock-out actions.');
                // }

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
                                    'date' => $firstShift->date,
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
                                    'date' => $secondShift->date,
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

                    if ($firstShiftStartTime > $firstShiftEndTime) {
                        $newFirstShiftStartTime = $firstShiftStartTime->subDay();

                        if ($firstClockInTime >= $firstShiftStartTime && $firstClockInTime >= $checkFirstLate) {
                            if ($firstClockOutTime >= $firstShiftEndTime && $firstClockOutTime >= $checkFirstOT) {
                                $firstTotalWork = $firstShiftEndTime->diffInMinutes($firstClockInTime);
                            } else {
                                $firstTotalWork = $firstClockOutTime->diffInMinutes($firstClockInTime);
                            }
                        } else {
                            if ($firstClockOutTime >= $firstShiftEndTime && $firstClockOutTime >= $checkFirstOT) {
                                $firstTotalWork = $firstShiftEndTime->diffInMinutes($newFirstShiftStartTime);
                            } else {
                                $firstTotalWork = $firstClockOutTime->diffInMinutes($newFirstShiftStartTime);
                            }
                        }
                    } else {
                        if ($firstClockInTime >= $firstShiftStartTime && $firstClockInTime >= $checkFirstLate) {
                            if ($firstClockOutTime >= $firstShiftEndTime && $firstClockOutTime >= $checkFirstOT) {
                                $firstTotalWork = $firstShiftEndTime->diffInMinutes($firstClockInTime);
                            } else {
                                $firstTotalWork = $firstClockOutTime->diffInMinutes($firstClockInTime);
                            }
                        } else {
                            if ($firstClockOutTime >= $firstShiftEndTime && $firstClockOutTime >= $checkFirstOT) {
                                $firstTotalWork = $firstShiftEndTime->diffInMinutes($firstShiftStartTime);
                            } else {
                                $firstTotalWork = $firstClockOutTime->diffInMinutes($firstShiftStartTime);
                            }
                        }
                    }

                    // if ($lastClockInTime >= $secondShiftStartTime && $lastClockInTime >= $checkSecondLate) {
                    //     if ($lastClockOutTime >= $secondShiftEndTime && $lastClockOutTime >= $checkSecondOT) {
                    //         $secondTotalWork = $secondShiftEndTime->diffInMinutes($lastClockInTime);
                    //     }else if ($lastClockOutTime >= $secondShiftEndTime && $lastClockOutTime <= $checkSecondOT) {
                    //         $secondTotalWork = $lastClockOutTime->diffInMinutes($lastClockInTime);
                    //     }else if ($lastClockOutTime <= $secondShiftEndTime) {
                    //         $secondTotalWork = $lastClockOutTime->diffInMinutes($lastClockInTime);
                    //     }
                    // }
                    // else {
                    //     if ($lastClockOutTime >= $secondShiftEndTime && $lastClockOutTime >= $checkSecondOT) {
                    //         $secondTotalWork = $secondShiftEndTime->diffInMinutes($secondShiftStartTime);
                    //     }else if ($lastClockOutTime >= $secondShiftEndTime && $lastClockOutTime <= $checkSecondOT) {
                    //         $secondTotalWork = $lastClockOutTime->diffInMinutes($secondShiftStartTime);
                    //     }else if ($lastClockOutTime <= $secondShiftEndTime) {
                    //         $secondTotalWork = $lastClockOutTime->diffInMinutes($secondShiftStartTime);
                    //     }
                    // }


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

                    if ($secondShiftStartTime > $secondShiftEndTime) {
                        $newSecondShiftStartTime = $secondShiftStartTime->subDay();

                        if ($lastClockInTime >= $secondShiftStartTime && $lastClockInTime >= $checkSecondLate) {
                            if ($lastClockOutTime >= $secondShiftEndTime && $lastClockOutTime >= $checkSecondOT) {
                                $secondTotalWork = $secondShiftEndTime->diffInMinutes($lastClockInTime);
                            }else {
                                $secondTotalWork = $lastClockOutTime->diffInMinutes($lastClockInTime);
                            }
                        }else {
                            if ($lastClockOutTime >= $secondShiftEndTime && $lastClockOutTime >= $checkSecondOT) {
                                $secondTotalWork = $secondShiftEndTime->diffInMinutes($newSecondShiftStartTime);
                            }else {
                                $secondTotalWork = $lastClockOutTime->diffInMinutes($newSecondShiftStartTime);
                            }
                        }
                    } else {
                        if ($lastClockInTime >= $secondShiftStartTime && $lastClockInTime >= $checkSecondLate) {
                            if ($lastClockOutTime >= $secondShiftEndTime && $lastClockOutTime >= $checkSecondOT) {
                                $secondTotalWork = $secondShiftEndTime->diffInMinutes($lastClockInTime);
                            }else {
                                $secondTotalWork = $lastClockOutTime->diffInMinutes($lastClockInTime);
                            }
                        }else {
                            if ($lastClockOutTime >= $secondShiftEndTime && $lastClockOutTime >= $checkSecondOT) {
                                $secondTotalWork = $secondShiftEndTime->diffInMinutes($secondShiftStartTime);
                            }else {
                                $secondTotalWork = $lastClockOutTime->diffInMinutes($secondShiftStartTime);
                            }
                        }
                    }


                    $totalWorkInHours = number_format($secondTotalWork / 60, 2);
                    $recordData['total_work'] = $totalWorkInHours;
                }

            } else if (!empty($firstShift) && empty($secondShift)){

                // Check if the user has reached the limit (2 times for both clock in and clock out)
                // if ($status === 'Clock In' && $clockinCount >= 1) {
                //     // Clock In limit reached, display error message
                //     return redirect()->route('homepage')->with('error', 'You have reached the limit of clock-in actions.');
                // } elseif ($status === 'Clock Out' && $clockoutCount >= 1) {
                //     // Clock Out limit reached, display error message
                //     return redirect()->route('homepage')->with('error', 'You have reached the limit of clock-out actions.');
                // }

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
                                    'date' => $firstShift->date,
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

                    // $lastClockInTime = PunchRecord::where('employee_id', $user->id)
                    //     ->whereDate('created_at', $currentDate)
                    //     ->where('in', 'Clock In')
                    //     ->orderBy('created_at', 'desc')
                    //     ->first()
                    //     ->created_at;

                    $lastClockOutTime = now();

                    $checkLate = $firstShiftStartTime->copy()->addMinutes($lateThreshold);

                    $checkOT = $firstShiftEndTime->copy()->addMinutes($overtimeCalculation);

                    if ($firstShiftStartTime > $firstShiftEndTime) {
                        $newFirstShiftStartTime = $firstShiftStartTime->subDay();
                        if ($firstClockInTime >= $firstShiftStartTime && $firstClockInTime >= $checkLate) {
                            if ($lastClockOutTime >= $firstShiftEndTime && $lastClockOutTime >= $checkOT) {
                                $totalWork = $firstShiftEndTime->diffInMinutes($firstClockInTime);
                            } else{
                                $totalWork = $lastClockOutTime->diffInMinutes($firstClockInTime);
                            }
                        } else {
                            if ($lastClockOutTime >= $firstShiftEndTime && $lastClockOutTime >= $checkOT) {
                                $totalWork = $firstShiftEndTime->diffInMinutes($newFirstShiftStartTime);
                            } else {
                                $totalWork = $lastClockOutTime->diffInMinutes($newFirstShiftStartTime);
                            }
                        }
                    } else {
                        if ($firstClockInTime >= $firstShiftStartTime && $firstClockInTime >= $checkLate) {
                            if ($lastClockOutTime >= $firstShiftEndTime && $lastClockOutTime >= $checkOT) {
                                $totalWork = $firstShiftEndTime->diffInMinutes($firstClockInTime);
                            } else{
                                $totalWork = $lastClockOutTime->diffInMinutes($firstClockInTime);
                            }
                        } else {
                            if ($lastClockOutTime >= $firstShiftEndTime && $lastClockOutTime >= $checkOT) {
                                $totalWork = $firstShiftEndTime->diffInMinutes($firstShiftStartTime);
                            } else {
                                $totalWork = $lastClockOutTime->diffInMinutes($firstShiftStartTime);
                            }
                        }
                    }


                    $totalWorkInHours = number_format($totalWork / 60, 2);
                    $recordData['total_work'] = $totalWorkInHours;
                }

            }

            $record = PunchRecord::create($recordData);

            return redirect()->route('homepage');
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

            $record = PunchRecord::create($recordData);

            return redirect()->route('homepage')->with('error', 'Schedule information not found.');
        }
    }
}
