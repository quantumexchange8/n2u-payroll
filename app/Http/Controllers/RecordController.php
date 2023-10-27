<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PunchRecord;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
class RecordController extends Controller
{
    //

    // public function clock_in(Request $request){
    //     $user = Auth::user();
       
    //     // Determine whether the user is clocking in or out based on the button text.
    //     $status = $request->input('status'); // Assuming 'status' corresponds to the button text.
    
    //     $recordData = [
    //         'employee_id' => $user->employee_id,
    //         'in' => $status === 'Clock In' ? 'Clock In' : null,
    //         'out' => $status === 'Clock Out' ? 'Clock Out' : null,
    //         'status' =>  null,
    //         'ot_approval' => null,
    //     ];

    //     $record = PunchRecord::create($recordData);

    //     return redirect()->route('homepage');
    // }

    public function clock_in(Request $request) {
        $user = Auth::user();
    
        // Determine whether the user is clocking in or out based on the button text.
        $status = $request->input('status'); // Assuming 'status' corresponds to the button text

        // Fetch the schedule information for the user.
        $schedule = DB::table('schedules')
            ->join('users', 'schedules.employee_id', '=', 'users.id')
            ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')
            ->select('schedules.id', 'schedules.employee_id', 'users.full_name', 'shifts.shift_start', 'shifts.shift_end')
            ->where('schedules.employee_id', $user->id)
            ->first();

        // Get the "Late Threshold Minutes" setting value
        $lateThreshold = Setting::where('setting_name', 'Late Threshold Minutes')->value('value');
    
        // Fetch the "Overtime Calculation" setting value
        $overtimeCalculation = Setting::where('setting_name', 'Overtime Calculation')->value('value');
        
       
    
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
    
            // Determine the 'in' and 'out' fields based on the button text.
            $recordData = [
                'employee_id' => $user->employee_id,
                'in' => $status === 'Clock In' ? 'Clock In' : null,
                'out' => $status === 'Clock Out' ? 'Clock Out' : null,
                'ot_approval' => null, // Your other fields here
                'remarks' => null
            ];

            
    
            // Determine the 'status' based on the button text and clock-in/clock-out time.
            if ($status === 'Clock In') {
                if ($recordData['in'] === 'Clock In' && $currentTime->greaterThan($shiftStartTime)) {
                    
                    // Calculate late threshold time
                    $lateThresholdTime = $shiftStartTime->copy()->addMinutes($lateThreshold);
    
                    if ($currentTime->greaterThan($lateThresholdTime)) {
                        $recordData['status'] = 'Late';
                    } else {
                        $recordData['status'] = 'On Time';
                    }
                } else {
                    $recordData['status'] = 'On Time';
                }
            }
            elseif ($status === 'Clock Out') {
                if ($currentTime->lessThanOrEqualTo($shiftEndTime)) {
                    $recordData['status'] = 'On Time';
                } else {
                    // Get the "Overtime Calculation" setting value
                    $overtimeCalculationMinutes = intval($overtimeCalculation);
    
                    // Calculate the overtime threshold time
                    $overtimeThresholdTime = $shiftEndTime->copy()->addMinutes($overtimeCalculationMinutes);
    
                    if ($currentTime->greaterThan($overtimeThresholdTime)) {
                        $recordData['status'] = 'Overtime';
                        $recordData['ot_approval'] = 'Pending';
                    } else {
                        $recordData['status'] = 'On Time';
                    }
                }
            }
    
            $record = PunchRecord::create($recordData);
    
            return redirect()->route('homepage');
        }
    
        // Handle the case where schedule information is not found.
        return redirect()->route('homepage')->with('error', 'Schedule information not found.');
    }
    
    
    
       
}
