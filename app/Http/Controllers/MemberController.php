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

        $settings = Setting::all();

        $tasks = Task::where('employee_id', $user->id)->with('user')->get();

        $periods = Period::all();

        $punchRecords = PunchRecord::with('user')->get();
        //dd($punchRecords);

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
            'settings' => $settings,
            'tasks' => $tasks,
            'periods' => $periods
        ]);
    }

    public function viewSchedule(Request $request) {
        // Check if the request wants JSON data
        if ($request->wantsJson()) {

            $userId = $request->input('user_id');
            // Retrieve and return joined data for FullCalendar
            $joinedData = Schedule::join('users', 'schedules.employee_id', '=', 'users.id')
                ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')
                ->where('users.id', $userId)
                ->select(
                    'schedules.id',
                    'schedules.employee_id',
                    'users.full_name',
                    'shifts.shift_start as shiftStart', // Alias shift_start to shiftStart
                    'shifts.shift_end as shiftEnd',     // Alias shift_end to shiftEnd
                    'schedules.date'
                )
                ->get();

            return response()->json($joinedData);
        }

        $user = User::where('id', '=', Auth::user()->id)->first();

        $schedules = Schedule::all();
        $shifts = Shift::all();

        return view('user.viewSchedule', compact('schedules', 'user', 'shifts'));
    }

    public function viewProfile(){
        $user = auth()->user(); // Retrieve the currently logged-in user
        $positions = Position::all();
        return view('user.viewProfile', compact('user', 'positions'));
    }

    public function updateProfile(Request $request) {
        // Use the currently authenticated user
        $user = User::where('id', '=', Auth::user()->id)->first();

        // Validate the input
        $request->validate([
            'full_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // Update the user's data
        $user->full_name = $request->input('full_name');
        $user->address = $request->input('address');
        $user->email = $request->input('email');

        // Save the changes
        $user->save();

        // Check if the user wants to update their password
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
                    Alert::error('Password Mismatch', 'New and retyped passwords do not match.');
                }
            } else {
                Alert::error('Incorrect Password', 'Your old password is incorrect.');
            }
        } else {
            Alert::success('Done', 'Successfully Updated');
        }

        return redirect()->route('viewProfile');
    }

    public function getdata()
    {
        $punchRecords = PunchRecord::with('user')->get();
        //dd($punchRecords);

        // Modify the date and time columns
        $punchRecords->each(function ($punchRecord) {
            $punchRecord->date = Carbon::parse($punchRecord->created_at)->toDateString();
            $punchRecord->time = Carbon::parse($punchRecord->created_at)->toTimeString();
        });

        return $punchRecords;
    }




}
