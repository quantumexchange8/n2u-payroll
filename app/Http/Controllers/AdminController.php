<?php

namespace App\Http\Controllers;

use App\Models\PunchRecord;
use App\Models\User;
use App\Models\Position;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\EmployeeRequest;
use Carbon\Carbon;

class AdminController extends Controller
{
    
    public function Admindashboard()
    {

        $punchRecords = PunchRecord::with('users')->get();
        //dd($punchRecords);

        // Modify the date and time columns
        $punchRecords->each(function ($punchRecord) {
            $punchRecord->date = Carbon::parse($punchRecord->created_at)->toDateString();
            $punchRecord->time = Carbon::parse($punchRecord->created_at)->toTimeString();
        });

        return view('admin.record', [
            'punchRecords' => $punchRecords
        ]);
    }

    public function viewEmployee() {
        $users = User::where('role', 'member')->with('position')->get(); // Eager load the positions
        $positions = Position::all(); // Fetch all positions
        return view('admin.viewEmployee', compact('users', 'positions'));
    }
    

    public function createEmployee(){
        $positions = Position::all();
        return view('admin.createEmployee', compact('positions'));
    }

    public function addEmployee(EmployeeRequest $request){        
        User::create([
            'employee_id' => $request->employee_id,
            'full_name' => $request->full_name,
            'ic_number' => $request->ic_number,
            'address' => $request->address,
            'email' => $request->email,
            'position_id' => $request->position_id,
            'employee_type' => $request->employee_type,
            'working_hour' => $request->working_hour,
            'employed_since' => $request->employed_since,
            'nation' => $request->nation,
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
            'passport_size_photo' => $request->passport_size_photo,
            'ic_photo' => $request->ic_photo,
            'offer_letter' => $request->offer_letter,
            'password' => Hash::make($request->password)
        ]);

        Alert::success('Congrats', 'Successfully Registered');
        return redirect()->route('viewEmployee');        
    }
   
    public function editEmployee($id) {
        $user = User::with('position')->find($id);
        $positions = Position::all(); // Fetch all positions
        //dd($user); // Add this line to check the retrieved data
        return view('admin.editEmployee', compact('user', 'positions'));
    }
    
    public function updateEmployee(Request $request, $id){
        $data = User::find($id);

        //Update the user's data based on the form input
        $data->update([
            'employee_id' => $request->input('employee_id'),
            'full_name' => $request->input('full_name'),
            'ic_number' => $request->input('ic_number'),
            'address' => $request->input('address'),
            'email' => $request->input('email'),
            'position_id' => $request->input('position_id'),
            'employee_type' => $request->input('employee_type'),
            'working_hour' => $request->input('working_hour'),
            'employed_since' => $request->input('employed_since'),
            'nation' => $request->input('nation'),
            'bank_name' => $request->input('bank_name'),
            'bank_account' => $request->input('bank_account'),
            'passport_size_photo' => $request->input('passport_size_photo'),
            'ic_photo' => $request->input('ic_photo'),
            'offer_letter' => $request->input('offer_letter'),
            // 'password'  => $request->input('password'),
        ]);

        Alert::success('Congrats', 'Successfully Updated');
        return redirect()->route('viewEmployee');
    }

    public function deleteEmployee($id){

        $employee = User::find($id);

        if (!$employee) {
            return redirect()->route('viewEmployee')->with('error', 'Employee not found');
        }

        $employee->delete(); // Soft delete the employee
        Alert::success('Congrats', 'Successfully Deleted');
        return redirect()->route('viewEmployee');
    }

    public function viewPosition(){
        $positions = Position::all();
        //dd($users);
        return view('admin.viewPosition', ['positions' => $positions]);
    }
    
    public function createPosition(){
        return view('admin.createPosition');
    }

    public function addPosition(Request $request){
        //dd($request->all());

        $data = $request->validate([
            'position_id' => 'required',
            'position' => 'required'
        ]);

        if($data){
            Position::create($data);
        } else {
            return redirect()->back();
        }

        return redirect()->route('viewPosition');
    }

    public function editPosition($id){
        $position = Position::find($id);
        
        return view('admin.editPosition', ['position' => $position]);
    }

    public function updatePosition(Request $request, $id){
        $data = Position::find($id);

        //Update the user's data based on the form input
        $data->update([
            'position_id' => $request->input('position_id'),
            'position' => $request->input('position'),
        ]);

        Alert::success('Congrats', 'Successfully Updated');
        return redirect()->route('viewPosition');
    }

    public function deletePosition($id){

        $position = Position::find($id);

        if (!$position) {
            return redirect()->route('viewPosition');
        }

        $position->delete(); // Soft delete the employee

        Alert::success('Congrats', 'Successfully Deleted');
        return redirect()->route('viewPosition');
    }

    public function viewShift(){
        $shifts = Shift::all();
        //dd($users);
        return view('admin.viewShift', ['shifts' => $shifts]);
    }
    
    public function createShift(){
        return view('admin.createShift');
    }

    public function addShift(Request $request){
        //dd($request->all());

        $data = $request->validate([
            'shift_id' => 'required',
            'shift_name' => 'required',
            'shift_start' => 'required',
            'shift_end' => 'required'
        ]);

        if($data){
            Shift::create($data);
        } else {
            return redirect()->back();
        }

        return redirect()->route('viewShift');
    }

    public function editShift($id){
        $shift = Shift::find($id);
        
        return view('admin.editShift', ['shift' => $shift]);
    }

    public function updateShift(Request $request, $id){
        $data = Shift::find($id);

        //Update the user's data based on the form input
        $data->update([
            'shift_id' => $request->input('shift_id'),
            'shift_name' => $request->input('shift_name'),
            'shift_start' => $request->input('shift_start'),
            'shift_end' => $request->input('shift_end')
        ]);

        Alert::success('Congrats', 'Successfully Updated');
        return redirect()->route('viewShift');
    }

    public function deleteShift($id){

        $shift = Shift::find($id);

        if (!$shift) {
            return redirect()->route('viewShift');
        }

        $shift->delete(); // Soft delete the employee

        Alert::success('Congrats', 'Successfully Deleted');
        return redirect()->route('viewShift');
    }
}
