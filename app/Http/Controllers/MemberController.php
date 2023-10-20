<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Shift;
use App\Models\Position;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class MemberController extends Controller
{
    public function dashboard(){

        $user = auth()->user(); // Assuming you are using Laravel's built-in authentication

        // Retrieve schedules related to the logged-in user (employee)
        $schedules = Schedule::where('employee_id', $user->id)->orderBy('date')->get();

        $shifts = Shift::all();

        return view('user.homepage', [
            'schedules' => $schedules,
            'shifts' => $shifts
        ]);
    }

    public function viewSchedule(Request $request){
        // Check if the request wants JSON data
        if ($request->wantsJson()) {
            // Retrieve and return joined data for FullCalendar
            $joinedData = Schedule::join('users', 'schedules.employee_id', '=', 'users.id')
                ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')
                ->select('schedules.id', 'schedules.employee_id', 'users.full_name', 'shifts.shift_start', 'shifts.shift_end', 'schedules.date')
                ->get();

            return response()->json($joinedData);
        }

        // If it's not a JSON request, return the view for the schedule page
        $schedules = Schedule::all();
        $users = User::where('role', 'member')->with('position')->get();
        $shifts = Shift::all();

        return view('user.viewSchedule', compact('schedules', 'users', 'shifts'));
    }


    // Define a private method to fetch schedules and shifts
    private function fetchSchedulesAndShifts(){
        // Get the currently logged-in user
        $user = auth()->user(); // Assuming you are using Laravel's built-in authentication

        // Retrieve schedules related to the logged-in user (employee)
        $schedules = Schedule::where('employee_id', $user->id)->orderBy('date')->get();

        $shifts = Shift::all();

        return [
            'schedules' => $schedules,
            'shifts' => $shifts,
        ];
    }

    public function viewJanuary(){
        $data = $this->fetchSchedulesAndShifts();

        return view('user.viewJanuary', $data);
    }

    public function viewFebruary(){
        $data = $this->fetchSchedulesAndShifts();

        return view('user.viewFebruary', $data);
    }

    public function viewMarch(){
        $data = $this->fetchSchedulesAndShifts();

        return view('user.viewMarch', $data);
    }

    public function viewApril(){
        $data = $this->fetchSchedulesAndShifts();

        return view('user.viewApril', $data);
    }

    public function viewMay(){
        $data = $this->fetchSchedulesAndShifts();

        return view('user.viewMay', $data);
    }

    public function viewJune(){
        $data = $this->fetchSchedulesAndShifts();

        return view('user.viewJune', $data);
    }

    public function viewJuly(){
        $data = $this->fetchSchedulesAndShifts();

        return view('user.viewJuly', $data);
    }

    public function viewAugust(){
        $data = $this->fetchSchedulesAndShifts();

        return view('user.viewAugust', $data);
    }

    public function viewSeptember(){
        $data = $this->fetchSchedulesAndShifts();

        return view('user.viewSeptember', $data);
    }

    public function viewOctober(){
        $data = $this->fetchSchedulesAndShifts();

        return view('user.viewOctober', $data);
    }

    public function viewNovember(){
        $data = $this->fetchSchedulesAndShifts();

        return view('user.viewNovember', $data);
    }

    public function viewDecember(){
        $data = $this->fetchSchedulesAndShifts();

        return view('user.viewDecember', $data);
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
    
    
    
    
}
