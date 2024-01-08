<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Shift;
use App\Models\Duty;
use App\Models\Position;
use App\Models\PunchRecord;
use App\Models\Setting;
use App\Models\Task;
use App\Models\Period;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class MemberController extends Controller
{
    public function dashboard(){

        // Assuming you are using Laravel's built-in authentication
        $user = auth()->user();
        $status = Auth::user()->status;

        if($status == 1){
            $clock = 'Clock In';
        } elseif($status == 2){
            $clock = 'Clock Out';
        } elseif($status == 3) {
            $clock = 'Clock Out for lunch';
        }elseif($status == 4) {
            $clock = 'Clock In for lunch';
        }
        // Retrieve schedules related to the logged-in user (employee)
        $schedules = Schedule::where('employee_id', $user->id)->orderBy('date')->get();
        $shifts = Shift::all();
        $duties = Duty::all();
        $tasks = Task::where('employee_id', $user->id)->with('user')->get();
        $periods = Period::all();
        $punchRecords = PunchRecord::with('user')->get();

        // Modify the date and time columns
        $punchRecords->each(function ($punchRecord) {
            $punchRecord->date = Carbon::parse($punchRecord->created_at)->toDateString();
            $punchRecord->time = Carbon::parse($punchRecord->created_at)->toTimeString();
        });

        return view('user.homepage', [
            'schedules' => $schedules,
            'shifts' => $shifts,
            'punchRecords' => $punchRecords,
            'user' => $user,
            'duty' => $duties,
            'status' => $status,
            'clock' => $clock,
            'tasks' => $tasks,
            'periods' => $periods
        ]);
    }

    // Calendar view
    // public function viewSchedule(Request $request) {
    //     // Check if the request wants JSON data
    //     if ($request->wantsJson()) {

    //         $userId = $request->input('user_id');
    //         // Retrieve and return joined data for FullCalendar
    //         $joinedData = Schedule::join('users', 'schedules.employee_id', '=', 'users.id')
    //             ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')
    //             ->where('users.id', $userId)
    //             ->select(
    //                 'schedules.id',
    //                 'schedules.employee_id',
    //                 'users.full_name',
    //                 'shifts.shift_start as shiftStart', // Alias shift_start to shiftStart
    //                 'shifts.shift_end as shiftEnd',     // Alias shift_end to shiftEnd
    //                 'schedules.date'
    //             )
    //             ->get();

    //         return response()->json($joinedData);
    //     }

    //     $user = User::where('id', '=', Auth::user()->id)->first();

    //     $schedules = Schedule::all();
    //     $shifts = Shift::all();

    //     return view('user.viewSchedule', compact('schedules', 'user', 'shifts'));
    // }

    // Card view
    public function viewSchedule(Request $request){
        $user = User::where('id', '=', Auth::user()->id)->first();
        $today = Carbon::now()->toDateString();

        $schedules = Schedule::join('shifts', 'schedules.shift_id', 'shifts.id')
                            ->where('schedules.employee_id', $user->id)
                            ->get();

        $tasks = Task::join('duties', 'tasks.duty_id', 'duties.id')
                    ->join('periods', 'tasks.period_id', 'periods.id')
                    ->where('tasks.employee_id', $user->id)
                    ->get();

        return view('user.viewSchedule', compact('schedules', 'user', 'tasks'));
    }

    public function viewProfile(){
        $user = auth()->user();
        $positions = Position::all();
        return view('user.viewProfile', compact('user', 'positions'));
    }

    public function updateProfile(Request $request) {

        $user = User::where('id', '=', Auth::user()->id)->first();

        $rules = [
            'full_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'old-pass' => 'required_with:new-pass',
            'new-pass' => 'nullable|required_with:old-pass|different:old-pass',
            'retype-pass' => 'nullable|required_with:new-pass|same:new-pass',
        ];

        $messages = [
            'full_name.required' => 'The Full Name is required.',
            'full_name.max' => 'The Full Name should not exceed 255 characters.',
            'address.max' => 'The Address should not exceed 255 characters.',
            'email.email' => 'The Email must be a valid email address.',
            'email.max' => 'The Email should not exceed 255 characters.',
            'old-pass.required_with' => 'The Old Password field is required when New Password is present.',
            'new-pass.required_with' => 'The New Password field is required when Old Password is present.',
            'new-pass.different' => 'The New Password must be different from the Old Password.',
            'retype-pass.required_with' => 'The Retype Password field is required when New Password is present.',
            'retype-pass.same' => 'The Retype Password and New Password must match.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->full_name = $request->input('full_name');
        $user->address = $request->input('address');
        $user->email = $request->input('email');

        $user->save();

        if ($request->filled('old-pass') && $request->filled('new-pass') && $request->filled('retype-pass')) {
            // Check if the old password matches the user's current password
            if (Hash::check($request->input('old-pass'), $user->password)) {
                // Check if the new password and retype password match
                if ($request->input('new-pass') === $request->input('retype-pass')) {
                    // Update the user's password
                    $user->password = Hash::make($request->input('new-pass'));
                    $user->save();
                    Alert::success('Password Updated', 'Your password has been updated.');
                } else {
                    Alert::error('Password Mismatch', 'New and retyped passwords do not match.')->persistent(true, false);
                }
            } else {
                Alert::error('Incorrect Password', 'Your old password is incorrect.')->persistent(true, false);
            }
        } else {
            Alert::success('Done', 'Profile updated successfully.')->persistent(true, false);
        }

        return redirect()->route('viewProfile');
    }

    public function getdata(){

        $punchRecords = PunchRecord::with('user')->get();

        $punchRecords->each(function ($punchRecord) {
            $punchRecord->date = Carbon::parse($punchRecord->created_at)->toDateString();
            $punchRecord->time = Carbon::parse($punchRecord->created_at)->toTimeString();
        });

        return $punchRecords;
    }

    public function getTasks(Request $request) {
        try {
            $date = $request->input('date');
            $userId = $request->input('user_id');

            $tasks = Task::join('periods', 'tasks.period_id', '=', 'periods.id')
                ->select('periods.period_name', 'tasks.start_time', 'tasks.end_time')
                ->where('tasks.date', $date)
                ->where('tasks.employee_id', $userId)
                ->get();

            return response()->json($tasks);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
