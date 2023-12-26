<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PunchRecord;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\Schedule;
use App\Models\OtApproval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{
    public function getUserStatus($userId){
        $user = User::where('employee_id', $userId)->first();

        if ($user) {
            return response()->json(['status' => $user->status]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
    }

    public function checkIn(Request $request) {

        $userId = $request->input('userId');
        $status = $request->input('status');
        $user = User::where('employee_id', $userId)->first();

        // Get the current date in the format 'YYYY-MM-DD'
        $currentDate = now()->toDateString();

        $currentDateTime = now();

        $currentTimes = $currentDateTime->format('H:i:s');

        // Fetch the schedule information for the user.
        $schedules = DB::table('schedules')
            ->join('users', 'schedules.employee_id', '=', 'users.id')
            ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')
            ->select('schedules.id', 'schedules.employee_id', 'users.full_name', 'shifts.shift_start', 'shifts.shift_end')
            ->where('schedules.employee_id', $user->id)
            ->whereDate('schedules.date', '=', $currentDate)
            ->whereNull('schedules.deleted_at')
            ->orderBy('shifts.shift_start', 'asc')
            ->get();

        // Get the "Late Threshold Minutes" setting value
        $lateThreshold = Setting::where('setting_name', 'Late Threshold (in minutes)')->value('value');

        // Fetch the "Overtime Calculation" setting value
        $overtimeCalculation = Setting::where('setting_name', 'Overtime Calculation (in minutes)')->value('value');

        if ($status === 'Clock In') {
            // Perform clock in
            // Update the user's status to 2 (clocked in)
            $user->status = 2;
            $user->save();
        } elseif ($status === 'Clock Out') {
            $user->status = 1;
            $user->save();
        }

        if ($schedules) {

            // Get the current time.
            $currentTime = now();

            // Check the count of schedules for the user.
            $scheduleCount = $schedules->count();

            // Define variables for shifts.
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

            // Determine the 'status_clock' based on the button text and the number of clock-ins.
            if ($status === 'Clock In') {
                $status_clock = $clockinCount + $clockoutCount + 1;
            } elseif ($status === 'Clock Out') {
                $status_clock = $clockinCount + $clockoutCount + 1;
            }

            // Determine the 'in' and 'out' fields based on the button text.
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
                                    'employee_id' => Auth::user()->employee_id,
                                    'date' => $currentDate,
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
                                $shiftEnd = $firstShift->shift_end;

                                $clockoutTime = \Carbon\Carbon::parse($clockout);
                                $shiftEndTime = \Carbon\Carbon::parse($shiftEnd);

                                $minutesDifference = $clockoutTime->diffInMinutes($shiftEndTime);

                                // You can also get the hours and minutes separately if needed
                                // $hours = floor($minutesDifference / 60);
                                $totalHours = $minutesDifference / 60;

                                $totalHoursRounded = number_format($totalHours, 2);

                                $newot = OtApproval::create([
                                    'employee_id' => Auth::user()->employee_id,
                                    'date' => $currentDate,
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

                    }else if ($clockinCount == 1 ) {

                        Alert::error('Failed', 'Oops');
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

                                // dd($totalHoursRounded);


                                $newot = OtApproval::create([
                                    'employee_id' => Auth::user()->employee_id,
                                    'date' => $currentDate,
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

                        Alert::error('Failed', 'Oops');
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

    public function login(){
        $users = User::where('role', 'member')->with('position')->get();
        return view('auth.login', compact('users'));
    }

    public function adminLogin(){
        return view('auth.adminLogin');
    }

    public function login_post(Request $request){

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'password' => 'required',
        ], [
            'employee_id.required' => 'Employee ID is required.',
            'password.required' => 'Password is required.',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Attempt to authenticate the user
        $credentials = [
            'employee_id' => $request->input('employee_id'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            // Authentication successful, get the authenticated user
            $user = Auth::user();

            // Check the user's role and redirect accordingly
            if ($user->role === 'member') {
                return redirect()->route('homepage'); // Redirect to member dashboard
            } elseif ($user->role === 'admin') {
                return redirect()->route('admindashboard'); // Redirect to admin dashboard
            }
        } else {
            // Authentication failed, show an error message

            return redirect()->back()->with('login_error', 'Invalid employee ID or password.');

        }
    }

    public function register(){
        return view('auth.register');
    }

    public function register_post(Request $request){

        $user = User::create([
            'name' => $request->f_name,
            'employee_id' => $request->e_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('login');
    }

    protected function authenticated(Request $request, $user){
        return redirect()->to('dashboard');
    }

    public function logout(){
        Session::flush();
        Auth::logout();

        return redirect('login');
    }
}
