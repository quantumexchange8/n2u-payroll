<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PunchRecord;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
class RecordController extends Controller
{
    //



    public function clock_in(Request $request) {
        $user = Auth::user(); 

        // Determine whether the user is clocking in or out based on the button text.
        $status = $request->input('status'); // Assuming 'status' corresponds to the button text
      
        $currentDate = now()->toDateString(); // Get the current date in the format 'YYYY-MM-DD'
        // Fetch the schedule information for the user.
        $schedule = DB::table('schedules')
            ->join('users', 'schedules.employee_id', '=', 'users.id')
            ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')
            ->select('schedules.id', 'schedules.employee_id', 'users.full_name', 'shifts.shift_start', 'shifts.shift_end')
            ->where('schedules.employee_id', $user->id)
            ->whereDate('schedules.date', '=', $currentDate)
            ->first();

        // Get the "Late Threshold Minutes" setting value
        $lateThreshold = Setting::where('setting_name', 'Late Threshold Minutes')->value('value');
    
        // Fetch the "Overtime Calculation" setting value
        $overtimeCalculation = Setting::where('setting_name', 'Overtime Calculation')->value('value');

        $user = Auth::user();

        $status = $request->input('status');
        
        if ($status === 'Clock In' && $user->status == 1) {
            // dd();
            // Perform clock in
            // Update the user's status to 2 (clocked in)
            $user->status = 2;
            $user->save();
        } elseif ($status === 'Clock Out' && $user->status == 2) {
            $user->status = 1;
            $user->save();
        }
    
        if ($schedule) {
            // Get the current time.
            $currentTime = now();
            $shiftStartTime = null;
            $shiftEndTime = null;
    
            if (!empty($schedule->shift_start)) {
                $shiftStartTime = now()->setTimeFromTimeString($schedule->shift_start);
            }
    
            if (!empty($schedule->shift_end)) {
                $shiftEndTime = now()->setTimeFromTimeString($schedule->shift_end);
                // If the shift end time is before the shift start time, it means it spans across two dates
                if ($shiftEndTime->lessThan($shiftStartTime)) {
                    // Increment the current date to the next day
                    $shiftEndTime->addDay();
                }
            }

            if($status == 'Clock In')
            {
                $status_clock = 1;
            } elseif($status == 'Clock Out')
            {
                $status_clock = 2;
            }
    
            // Determine the 'in' and 'out' fields based on the button text.
            $recordData = [
                'employee_id' => $user->id,
                'in' => $status === 'Clock In' ? 'Clock In' : null,
                'out' => $status === 'Clock Out' ? 'Clock Out' : null,
                'ot_approval' => null, // Your other fields here
                'remarks' => null,
                'status_clock' => $status_clock
            ];

            // $user_status = User::update([
            //     'status' => '2'
            // ]);

            // Count the number of clock-ins for the current date
            $clockinCount = PunchRecord::where('employee_id', $user->id)
            ->whereDate('created_at', $currentDate)
            ->where('in', 'Clock In')
            ->count();
       
    
            // Determine the 'status' based on the button text and clock-in/clock-out time.
            if ($status === 'Clock In') {

                if($clockinCount != 1){
                    if ($recordData['in'] === 'Clock In' && $currentTime->greaterThan($shiftStartTime)) {
                    
                        // Calculate late threshold time
                        $lateThresholdTime = $shiftStartTime->copy()->addMinutes($lateThreshold);
        
                        if ($currentTime->greaterThan($lateThresholdTime)) {
                            $recordData['status'] = 'Late';
                        } else {
                            $recordData['status'] = 'On-Time';
                        }
                    } else {
                        $recordData['status'] = 'On-Time';
                    }
                } else{
                    $recordData['status'] = 'On-Time';
                }

            }
            elseif ($status === 'Clock Out') {
                if ($currentTime->lessThanOrEqualTo($shiftEndTime)) {
                    $recordData['status'] = 'On-Time';
                } else {
                    // Get the "Overtime Calculation" setting value
                    $overtimeCalculationMinutes = intval($overtimeCalculation);
    
                    // Calculate the overtime threshold time
                    $overtimeThresholdTime = $shiftEndTime->copy()->addMinutes($overtimeCalculationMinutes);
    
                    if ($currentTime->greaterThan($overtimeThresholdTime)) {
                        $recordData['status'] = 'Overtime';
                        $recordData['ot_approval'] = 'Pending';
                    } else {
                        $recordData['status'] = 'On-Time';
                    }
                }
            }
    
            $record = PunchRecord::create($recordData);
    
            return redirect()->route('homepage');
        }else {
            // Schedule information not found, insert "Clock In" and "Clock Out" records with null values
            $recordData = [
                'employee_id' => $user->id,
                'in' => $status === 'Clock In' ? 'Clock In' : null,
                'out' => $status === 'Clock Out' ? 'Clock Out' : null,
                'ot_approval' => null,
                'remarks' => null,
                'status_clock' => 1
            ];
    
            $record = PunchRecord::create($recordData);
    
            return redirect()->route('homepage')->with('error', 'Schedule information not found.');
        }
    }
    
    
    
       
}
