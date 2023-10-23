<?php

namespace App\Http\Controllers;

use App\Models\PunchRecord;
use App\Models\User;
use App\Models\Position;
use App\Models\Department;
use App\Models\Duty;
use App\Models\Shift;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\EmployeeRequest;
use Carbon\Carbon;

class AdminController extends Controller
{
    
    public function Admindashboard()
    {

        $punchRecords = PunchRecord::with('user')->get();
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
        // Validate the incoming request data
        $validatedData = $request->validated();

        // Handle passport size photo
        if ($request->hasFile('passport_size_photo')) {
            $file = $request->file('passport_size_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = $validatedData['full_name'] . '_photo.' . $extension; // Modify the file name
            $file->move('uploads/employee/passportSizePhoto/', $filename);
            $validatedData['passport_size_photo'] = $filename;
        }

        // Handle IC photo
        if ($request->hasFile('ic_photo')) {
            $file = $request->file('ic_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = $validatedData['full_name'] . '_ic.' . $extension; // Modify the file name
            $file->move('uploads/employee/icPhoto/', $filename);
            $validatedData['ic_photo'] = $filename;
        }

        // Handle offer letter
        if ($request->hasFile('offer_letter')) {
            $file = $request->file('offer_letter');
            $extension = $file->getClientOriginalExtension();
            $filename = $validatedData['full_name'] . '_offer_letter.' . $extension; // Modify the file name
            $file->move('uploads/employee/offerLetter/', $filename);
            $validatedData['offer_letter'] = $filename;
        }

        // Create the user record with the validated and modified data
        User::create($validatedData);

        Alert::success('Done', 'Successfully Registered');
        return redirect()->route('viewEmployee'); 
    }
   
    public function editEmployee($id) {
        $user = User::with('position')->find($id);
        $positions = Position::all(); // Fetch all positions
        //dd($user); // Add this line to check the retrieved data
        return view('admin.editEmployee', compact('user', 'positions'));
    }
    
    public function updateEmployee(EmployeeRequest $request, $id) {
        $data = User::find($id);
    
        // Validate the incoming request data
        $validatedData = $request->validated();
    
        // Handle file uploads if necessary
        $photoPath = 'uploads/employee/passportSizePhoto/';
        $icPath = 'uploads/employee/icPhoto/';
        $offerLetterPath = 'uploads/employee/offerLetter/';
    
        // Handle passport size photo
        if ($request->hasFile('passport_size_photo')) {
            $photo = $request->file('passport_size_photo');
            $photoExtension = $photo->getClientOriginalExtension();
            $photoName = $data->full_name . '_photo.' . $photoExtension;
    
            // Delete all previous files with the same full name
            $filesToDelete = glob($photoPath . $data->full_name . '_photo.*');
            foreach ($filesToDelete as $fileToDelete) {
                if (File::exists($fileToDelete)) {
                    File::delete($fileToDelete);
                }
            }
    
            // Upload the new passport size photo
            $photo->move($photoPath, $photoName);
    
            // Update the database field with the new file name
            $validatedData['passport_size_photo'] = $photoName;
        }
    
        // Handle IC photo
        if ($request->hasFile('ic_photo')) {
            $icPhoto = $request->file('ic_photo');
            $icExtension = $icPhoto->getClientOriginalExtension();
            $icName = $data->full_name . '_ic.' . $icExtension;
    
            // Delete all previous files with the same full name
            $filesToDelete = glob($icPath . $data->full_name . '_ic.*');
            foreach ($filesToDelete as $fileToDelete) {
                if (File::exists($fileToDelete)) {
                    File::delete($fileToDelete);
                }
            }
    
            // Upload the new IC photo
            $icPhoto->move($icPath, $icName);
    
            // Update the database field with the new file name
            $validatedData['ic_photo'] = $icName;
        }
    
        // Handle offer letter
        if ($request->hasFile('offer_letter')) {
            $offerLetter = $request->file('offer_letter');
            $offerLetterExtension = $offerLetter->getClientOriginalExtension();
            $offerLetterName = $data->full_name . '_offer_letter.' . $offerLetterExtension;
    
            // Delete all previous files with the same full name
            $filesToDelete = glob($offerLetterPath . $data->full_name . '_offer_letter.*');
            foreach ($filesToDelete as $fileToDelete) {
                if (File::exists($fileToDelete)) {
                    File::delete($fileToDelete);
                }
            }
    
            // Upload the new offer letter
            $offerLetter->move($offerLetterPath, $offerLetterName);
    
            // Update the database field with the new file name
            $validatedData['offer_letter'] = $offerLetterName;
        }
    
        // Update the user's data based on the validated form input
        $data->update($validatedData);
    
        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('viewEmployee');
    }

    public function updateEmployeePassword(Request $request, $id){
        // Validate the input, including your password requirements
        $request->validate([
            'new_password' => ['required', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)/'],
        ]);
    
        // Find the user by ID
        $user = User::find($id);
    
        // Hash the new password
        $hashedPassword = Hash::make($request->input('new_password'));
    
        // Update the user's password
        $user->password = $hashedPassword;
        $user->save();
    
        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('viewEmployee');
    }
    

    public function deleteEmployee($id){

        $employee = User::find($id);

        if (!$employee) {
            return redirect()->route('viewEmployee')->with('error', 'Employee not found');
        }

        $employee->delete(); // Soft delete the employee
        Alert::success('Done', 'Successfully Deleted');
        return redirect()->route('viewEmployee');
    }

    public function viewPosition(){
        $positions = Position::all();
        $departments = Department::all();
        return view('admin.viewPosition', compact('positions', 'departments'));
    }
    
    public function createPosition(){
        $departments = Department::all();
        return view('admin.createPosition', compact('departments'));
    }

    public function addPosition(Request $request){
        $data = $request->validate([
            'position_name' => 'required',
            'department_id' => 'nullable'
        ]);
    
        if ($data) {
            $position = new Position();
            $position->position_id = $position->generatePositionId();
            $position->position_name = $data['position_name'];
            $position->department_id = $data['department_id'];
            $position->save();
    
            Alert::success('Done', 'Successfully Inserted');
        } else {
            return redirect()->back();
        }
    
        return redirect()->route('viewPosition');
    }
    
    public function editPosition($id){
        $positions = Position::with('department')->find($id);
        $departments = Department::all();
        return view('admin.editPosition', compact('positions', 'departments'));
    }

    public function updatePosition(Request $request, $id){
        $data = Position::find($id);

        //Update the user's data based on the form input
        $data->update([
            'position_name' => $request->input('position_name'),
            'department_id' => $request->input('department_id')
        ]);

        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('viewPosition');
    }

    public function deletePosition($id){

        $position = Position::find($id);

        if (!$position) {
            return redirect()->route('viewPosition');
        }

        $position->delete(); // Soft delete the employee

        Alert::success('Done', 'Successfully Deleted');
        return redirect()->route('viewPosition');
    }

    public function viewDepartment(){
        $departments = Department::all();
  
        return view('admin.viewDepartment', ['departments' => $departments]);
    }
    
    public function createDepartment(){
        return view('admin.createDepartment');
    }

    public function addDepartment(Request $request){
        $data = $request->validate([
            'department_name' => 'required'
        ]);
    
        if($data){
            $department = new Department();
            $department->department_id = $department->generateDepartmentId();
            $department->department_name = $data['department_name'];
            $department->save();
    
            Alert::success('Done', 'Successfully Inserted');
        } else {
            return redirect()->back();
        }
    
        return redirect()->route('viewDepartment');
    }
    

    public function editDepartment($id){
        $department = Department::find($id);
        
        return view('admin.editDepartment', ['department' => $department]);
    }

    public function updateDepartment(Request $request, $id){
        $data = Department::find($id);

        //Update the user's data based on the form input
        $data->update([
            'department_name' => $request->input('department_name')
        ]);

        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('viewDepartment');
    }

    public function deleteDepartment($id){

        $department = Department::find($id);

        if (!$department) {
            return redirect()->route('viewDepartment');
        }

        $department->delete(); // Soft delete the employee

        Alert::success('Done', 'Successfully Deleted');
        return redirect()->route('viewDepartment');
    }

    public function viewDuty(){
        $duties = Duty::all();
  
        return view('admin.viewDuty', ['duties' => $duties]);
    }
    
    public function createDuty(){
        return view('admin.createDuty');
    }

    public function addDuty(Request $request){
        $data = $request->validate([
            'duty_name' => 'required'
        ]);
    
        if($data){
            $duty = new Duty();
            $duty->duty_id = $duty->generateDutyId();
            $duty->duty_name = $data['duty_name'];
            $duty->save();
    
            Alert::success('Done', 'Successfully Inserted');
        } else {
            return redirect()->back();
        }
    
        return redirect()->route('viewDuty');
    }
    

    public function editDuty($id){
        $duty = Duty::find($id);
        
        return view('admin.editDuty', ['duty' => $duty]);
    }

    public function updateDuty(Request $request, $id){
        $data = Duty::find($id);

        //Update the user's data based on the form input
        $data->update([
            'duty_name' => $request->input('duty_name')
        ]);

        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('viewDuty');
    }

    public function deleteDuty($id){

        $duty = Duty::find($id);

        if (!$duty) {
            return redirect()->route('viewDuty');
        }

        $duty->delete(); // Soft delete the employee

        Alert::success('Done', 'Successfully Deleted');
        return redirect()->route('viewDepartment');
    }

    public function viewShift(){
        $shifts = Shift::all();
        return view('admin.viewShift', ['shifts' => $shifts]);
    }
    
    public function createShift(){
        return view('admin.createShift');
    }

    public function addShift(Request $request){

        $data = $request->validate([
            'shift_name' => 'required',
            'shift_start' => 'required',
            'shift_end' => 'required'
        ]);

        if($data){
            $shift = new Shift();
            $shift->shift_id = $shift->generateShiftId();
            $shift->shift_name = $data['shift_name'];
            $shift->shift_start = $data['shift_start'];
            $shift->shift_end = $data['shift_end'];
            $shift->save();


            Alert::success('Done', 'Successfully Inserted');
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
            'shift_name' => $request->input('shift_name'),
            'shift_start' => $request->input('shift_start'),
            'shift_end' => $request->input('shift_end')
        ]);

        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('viewShift');
    }

    public function deleteShift($id){

        $shift = Shift::find($id);

        if (!$shift) {
            return redirect()->route('viewShift');
        }

        $shift->delete(); // Soft delete the employee

        Alert::success('Done', 'Successfully Deleted');
        return redirect()->route('viewShift');
    }

    public function schedule(Request $request){
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

        return view('admin.schedule', compact('schedules', 'users', 'shifts'));
    }
  
    public function addSchedule(Request $request){

        $data = $request->validate([
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start', // Ensure date_end is after or equal to date_start
            'employee_id' => 'required',
            'shift_id' => 'required'
        ]);

        //dd($request->all());

        if($data){
            $start = Carbon::parse($data['date_start']);
            $end = Carbon::parse($data['date_end']);
            $dates = [];
    
            // Generate an array of dates between date_start and date_end
            while ($start->lte($end)) {
                $dates[] = $start->toDateString();
                $start->addDay();
            }
    
            foreach ($dates as $date) {
                $schedule = new Schedule();
                $schedule->schedule_id = $schedule->generateScheduleId();
                $schedule->date = $date;
                $schedule->employee_id = $data['employee_id'];
                $schedule->shift_id = $data['shift_id'];
                $schedule->save();
            }
    
            Alert::success('Done', 'Successfully Inserted');
        } else {
            
            return redirect()->back();
        }

        return redirect()->route('schedule');
    }

    public function deleteSchedule($id){

        $schedule = Schedule::find($id);

        if (!$schedule) {
            return redirect()->route('schedule');
        }

        $schedule->delete(); // Soft delete the employee

        // Alert::success('Done', 'Successfully Deleted');
        // return redirect()->route('schedule');
    }

    
    

    
}
