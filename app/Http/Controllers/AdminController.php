<?php

namespace App\Http\Controllers;

use App\Models\PunchRecord;
use App\Models\User;
use App\Models\Position;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function addEmployee(Request $request){
        //dd($request->all());

        $data = $request->validate([
            'employee_id' => 'required',
            'full_name' => ['required', 'regex:/^[a-zA-Z\s\/\']+$/'],
            'ic_number' => 'required',
            'address'=>'required',
            'email'=>'required|email',
            'position_id'=>'nullable',
            'employee_type'=>'nullable',
            'working_hour' => ['required', 'integer', 'min:1'],
            'bank_name'=>'nullable',
            'bank_account'=>'nullable|integer',
            'passport_size_photo'=>'nullable',
            'ic_photo'=>'nullable',
            'offer_letter'=>'nullable',
            'password' => ['required', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)/'],
        ]);

        //$selectedPositionId = $request->input('position_id');

        $data['password'] = Hash::make($request->password);

        if($data){
            User::create($data);
        } else {
            return redirect()->back();
        }

        return redirect()->route('viewEmployee');
    }
   
    public function editEmployee($id) {
        $user = User::with('position')->find($id);
        $positions = Position::all(); // Fetch all positions
        // dd($user); // Add this line to check the retrieved data
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
            'bank_name' => $request->input('bank_name'),
            'bank_account' => $request->input('bank_account'),
            'passport_size_photo' => $request->input('passport_size_photo'),
            'ic_photo' => $request->input('ic_photo'),
            'offer_letter' => $request->input('offer_letter'),
            // 'password'  => $request->input('password'),
        ]);

        return redirect()->route('viewEmployee');
    }

    public function deleteEmployee($id){

        $employee = User::find($id);

        if (!$employee) {
            return redirect()->route('viewEmployee')->with('error', 'Employee not found');
        }

        $employee->delete(); // Soft delete the employee

        return redirect()->route('viewEmployee')->with('success', 'Employee soft-deleted successfully');
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

        return redirect()->route('viewPosition');
    }

    public function deletePosition($id){

        $position = Position::find($id);

        if (!$position) {
            return redirect()->route('viewPosition');
        }

        $position->delete(); // Soft delete the employee

        return redirect()->route('viewPosition');
    }
}
