<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Duty;
use App\Models\Position;
use App\Models\PunchRecord;
use App\Models\SalaryLog;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\EmployeeRequest;
use Carbon\Carbon;

class AdminController extends Controller
{
    
    public function Admindashboard(){
        // Retrieve all punch records with associated user information
        $punchRecords = PunchRecord::with('user')->get();
    
        // Modify the date and time columns in each punch record
        $punchRecords->each(function ($punchRecord) {
            $punchRecord->date = Carbon::parse($punchRecord->created_at)->toDateString();
            $punchRecord->time = Carbon::parse($punchRecord->created_at)->toTimeString();
        });

        $pendingOTCount = PunchRecord::where('ot_approval', 'Pending')->count();
    
        // Retrieve all schedules, shifts, and settings
        $schedules = Schedule::all();
        $shifts = Shift::all();
        $settings = Setting::all();
    
        // Return the view with the punchRecords, schedules, shifts, and settings
        return view('admin.record', compact('punchRecords', 'schedules', 'shifts', 'settings', 'pendingOTCount'));
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

        $full_name_with_underscores = str_replace(' ', '_', $full_name);

        // Handle passport size photo
        if ($request->hasFile('passport_size_photo')) {
            $file = $request->file('passport_size_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = $full_name_with_underscores . '_photo.' . $extension; // Modify the file name
            $file->move('uploads/employee/passportSizePhoto/', $filename);
            $validatedData['passport_size_photo'] = $filename;
        }

        // Handle IC photo
        if ($request->hasFile('ic_photo')) {
            $file = $request->file('ic_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = $full_name_with_underscores . '_ic.' . $extension; // Modify the file name
            $file->move('uploads/employee/icPhoto/', $filename);
            $validatedData['ic_photo'] = $filename;
        }

        // Handle offer letter
        if ($request->hasFile('offer_letter')) {
            $file = $request->file('offer_letter');
            $extension = $file->getClientOriginalExtension();
            $filename = $full_name_with_underscores . '_offer_letter.' . $extension; // Modify the file name
            $file->move('uploads/employee/offerLetter/', $filename);
            $validatedData['offer_letter'] = $filename;
        }

        // Handle account pic
        if ($request->hasFile('account_pic')) {
            $file = $request->file('account_pic');
            $extension = $file->getClientOriginalExtension();
            $filename = $validatedData['full_name'] . '_account_pic.' . $extension; // Modify the file name
            $file->move('uploads/employee/accountPic/', $filename);
            $validatedData['account_pic'] = $filename;
        }

        // Handle other image
        if ($request->hasFile('other_image')) {
            $file = $request->file('other_image');
            $extension = $file->getClientOriginalExtension();
            $filename = $validatedData['full_name'] . '_other_image.' . $extension; // Modify the file name
            $file->move('uploads/employee/otherImage/', $filename);
            $validatedData['other_image'] = $filename;
        }
        
        // Create the user record with the validated and modified data
        User::create($validatedData);

        Alert::success('Done', 'Successfully Registered');
        return redirect()->route('viewEmployee'); 
    }
   
    public function editEmployee($id) {
        $user = User::with('position')->find($id);
        $positions = Position::all(); // Fetch all positions

        //Fetch passport size photo, ic photo and offer letter
        $passport_size_photo = $user->passport_size_photo;
        $ic_photo = $user->ic_photo;
        $offer_letter = $user->offer_letter;
        $account_pic = $user->account_pic;
        $other_image = $user->other_image;

        return view('admin.editEmployee', compact('user', 'positions', 'passport_size_photo', 'ic_photo', 'offer_letter', 'account_pic', 'other_image'));
    }
    
    public function updateEmployee(EmployeeRequest $request, $id) {
        $data = User::find($id);
    
        // Validate the incoming request data
        $validatedData = $request->validated();
    
        // Handle file uploads if necessary
        $photoPath = 'uploads/employee/passportSizePhoto/';
        $icPath = 'uploads/employee/icPhoto/';
        $offerLetterPath = 'uploads/employee/offerLetter/';
        $accountPicPath = 'uploads/employee/accountPic/';
    
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

        // Handle account pic
        if ($request->hasFile('account_pic')) {
            $accountPic = $request->file('account_pic');
            $accountPicExtension = $accountPic->getClientOriginalExtension();
            $accountPicName = $data->full_name . '_account_pic.' . $accountPicExtension;
    
            // Delete all previous files with the same full name
            $filesToDelete = glob($accountPicPath . $data->full_name . '_account_pic.*');
            foreach ($filesToDelete as $fileToDelete) {
                if (File::exists($fileToDelete)) {
                    File::delete($fileToDelete);
                }
            }
    
            // Upload the new offer letter
            $accountPic->move($accountPicPath, $accountPicName);
    
            // Update the database field with the new file name
            $validatedData['account_pic'] = $accountPicName;
        }
    
        // Update the user's data based on the validated form input
        $data->update($validatedData);
    
        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('viewEmployee');
    }

    public function updateEmployeePassword(Request $request, $id){
        // Validate the input
        $request->validate([
            'new_password' => ['required'],
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
            'department_id' => 'required'
        ]);
    
        if ($data) {
            $position = new Position();
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

        $shift->delete(); // Soft delete the employee

        Alert::success('Done', 'Successfully Deleted');
        return redirect()->route('viewShift');
    }

    public function schedule(Request $request){
        // Check if the request wants JSON data
        if ($request->wantsJson()) {
            // Retrieve and return joined data for FullCalendar
            $joinedData = Schedule::leftJoin('users', 'schedules.employee_id', '=', 'users.id')
                ->leftJoin('shifts', 'schedules.shift_id', '=', 'shifts.id')
                ->leftJoin('duties', 'schedules.duty_id', '=', 'duties.id')
                ->select('schedules.id', 'schedules.employee_id', 'users.full_name', 
                        'shifts.shift_start', 'shifts.shift_end', 'schedules.date', 
                        'duties.duty_name', 'schedules.remarks', 'schedules.off_day')
                ->get();

            return response()->json($joinedData);
        }

        // If it's not a JSON request, return the view for the schedule page
        $schedules = Schedule::all();
        $users = User::where('role', 'member')->with('position')->get();
        $shifts = Shift::all();
        $duties = Duty::all();

        return view('admin.schedule', compact('schedules', 'users', 'shifts', 'duties'));
    }
  
    public function addSchedule(Request $request){

        $data = $request->validate([
            'date_start' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_start', // Ensure date_end is after or equal to date_start
            'employee_id' => 'required',
            'shift_id' => 'nullable',
            'duty_id' => 'nullable',
            'remarks' => 'nullable',
            'off_day' => 'nullable',
        ]);

        if($data){
            $start = Carbon::parse($data['date_start']);
            $end = Carbon::parse($data['date_end']);
            $dates = [];
    
            if ($data['date_end'] === null) {
                // If date_end is null, insert a single schedule
                $dates[] = $start->toDateString();
            } else {
                // Generate an array of dates between date_start and date_end
                while ($start->lte($end)) {
                    $dates[] = $start->toDateString();
                    $start->addDay();
                }
            }
    
            foreach ($dates as $date) {
                $schedule = new Schedule();
                $schedule->date = $date;
                $schedule->employee_id = $data['employee_id'];
                if (isset($data['off_day']) && $data['off_day'] == 1) {
                    // Handle as an off day
                    $schedule->off_day = 1; // Set off_day to 1
                    $schedule->shift_id = null;
                    $schedule->duty_id = null;
                    $schedule->remarks = null;
                } else {
                    // Handle as a regular working day
                    $schedule->off_day = 0; // Set off_day to 0
                    $schedule->shift_id = $data['shift_id'];
                    $schedule->duty_id = $data['duty_id'];
                    $schedule->remarks = $data['remarks'];
                }
                $schedule->save();
            }
    
            Alert::success('Done', 'Successfully Inserted');
            return redirect()->route('schedule');
        } else {
            
            return redirect()->back();
        }
    }

    public function updateSchedule(Request $request, $id){

        $data = Schedule::find($id);
        // $user = User::where('full_name', $request->edit_employee_id)->first();

        // dd($request->off_day);
        $selectedUserId = $request->input('edit_employee_id');
        $selectedShiftId = $request->input('edit_shift_id');
        $selectedDutyId = $request->input('edit_duty_id');

        // $request->merge(['off_day' => 0]);

        $data->update([
            'employee_id' => $selectedUserId,
            'shift_id' => $selectedShiftId,
            'duty_id' => $selectedDutyId,
            'date' => $request->input('date'),
            'remarks' =>$request->input('remarks'),
            'off_day' => $request->off_day,
        ]);

        // dd($request->all());

        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('schedule');
    }

    public function deleteSchedule($id){

        $schedule = Schedule::find($id);

        $schedule->delete(); // Soft delete the employee

        // Alert::success('Done', 'Successfully Deleted');
        // return redirect()->route('schedule');
    }

    public function viewSetting(){
        $settings = Setting::all();
        return view('admin.viewSetting', ['settings' => $settings]);
    }
    
    public function createSetting(){
        return view('admin.createSetting');
    }

    public function addSetting(Request $request){

        $data = $request->validate([
            'setting_name' => 'required',
            'value' => 'required',
            'description' => 'nullable'
        ]);

        if($data){
            $setting = new Setting();
            $setting->setting_name = $data['setting_name'];
            $setting->value = $data['value'];
            $setting->description = $data['description'];
            $setting->save();


            Alert::success('Done', 'Successfully Inserted');
        } else {
            return redirect()->back();
        }

        return redirect()->route('viewSetting');
    }

    public function editSetting($id){
        $setting = Setting::find($id);
        
        return view('admin.editSetting', ['setting' => $setting]);
    }

    public function updateSetting(Request $request, $id){
        $data = Setting::find($id);

        //Update the user's data based on the form input
        $data->update([
            'setting_name' => $request->input('setting_name'),
            'value' => $request->input('value'),
            'description' => $request->input('description')
        ]);

        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('viewSetting');
    }

    public function deleteSetting($id){

        $setting = Setting::find($id);

        $setting->delete(); // Soft delete the employee

        Alert::success('Done', 'Successfully Deleted');
        return redirect()->route('viewSetting');
    }

    public function otApproval(){
        $punchRecords = PunchRecord::all();
        return view('admin.otApproval', ['punchRecords' => $punchRecords]);
    }
 

    public function editOtApproval($id){
        $punchRecords = PunchRecord::find($id);
        $users = User::all();
        
        return view('admin.editOtApproval', [
            'punchRecords' => $punchRecords,
            'users' => $users
        ]);
    }

    public function updateOtApproval(Request $request, $id) {
        $punchRecord = PunchRecord::find($id);
    
        if ($request->remark == null) {
            $punchRecord->ot_approval = 'Approved';
    
            // Retrieve the associated user
            $user = User::where('id', $punchRecord->employee_id)->first();
    
            // Check if the user exists and has schedules
            if ($user) {
                $schedule = Schedule::where('employee_id', $user->id)
                    ->whereDate('date', $punchRecord->created_at->toDateString())
                    ->first();
    
                if ($schedule) {
                    $shift = Shift::find($schedule->shift_id);

                    if ($shift) {
                        // Calculate the overtime hours based on the difference between created_at and shift end
                        $shiftEndTime = Carbon::createFromFormat('H:i', $shift->shift_end);
                        $createdTime = Carbon::parse($punchRecord->created_at);

                        // Calculate the difference in minutes between created_at and shift end
                        $minutesDifference = $createdTime->diffInMinutes($shiftEndTime);
    
                        // Convert the minutes to hours
                        $otHours = $minutesDifference / 60;

                        // Round otHours to 2 decimal places
                        $otHours = round($otHours, 2);
    
                        $punchRecord->ot_hours = $otHours;
                    }
                }
            }
    
            $punchRecord->save();
        } else {
            $punchRecord->ot_approval = 'Rejected';
            $punchRecord->remarks = $request->remark;
            $punchRecord->save();
        }
    
        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('otApproval');
    }
  
    public function deleteOtApproval($id){

        $punchRecords = PunchRecord::find($id);

        $punchRecords->delete(); // Soft delete the employee

        Alert::success('Done', 'Successfully Deleted');
        return redirect()->route('otApproval');
    }

    public function attendance(){
        // Retrieve all punch records with associated user information
        $punchRecords = PunchRecord::with('user')->get();
    
        // Modify the date and time columns in each punch record
        $punchRecords->each(function ($punchRecord) {
            $punchRecord->date = Carbon::parse($punchRecord->created_at)->toDateString();
            $punchRecord->time = Carbon::parse($punchRecord->created_at)->toTimeString();
        });
    
        // Retrieve all schedules, shifts, and settings
        $schedules = Schedule::all();
        $shifts = Shift::all();
        $settings = Setting::all();
    
        // Return the view with the punchRecords, schedules, shifts, and settings
        return view('admin.attendance', compact('punchRecords', 'schedules', 'shifts', 'settings'));
    }

    public function salaryLogs(){
        // Fetch all users with their positions
        $users = User::where('role', 'member')->with('position')->get();

        // Get the OT allowance value from the settings table
        $otAllowanceSetting = Setting::where('setting_name', 'OT Allowance')->first();

        if ($otAllowanceSetting) {
            $otAllowanceValue = (float) preg_replace('/[^0-9.]/', '', $otAllowanceSetting->value);
        } else {
            // Default OT allowance value in case the setting is not found
            $otAllowanceValue = 0;
        }
    
        // Loop through each user to calculate their total OT hours and update/create records for each month
        foreach ($users as $user) {
            $employeeId = $user->id;
    
            // Loop through each month
            for ($month = 1; $month <= 12; $month++) {
                // Query the punch_record table to check if the user has records for the current month
                $hasRecordsForMonth = PunchRecord::where('employee_id', $employeeId)
                    ->whereMonth('created_at', $month)
                    ->exists();
    
                // If the user has records for the current month, calculate total_ot_hour
                if ($hasRecordsForMonth) {
                    $otHoursForMonth = PunchRecord::selectRaw('SUM(ot_hours) as total_ot_hour')
                        ->where('employee_id', $employeeId)
                        ->whereMonth('created_at', $month)
                        ->value('total_ot_hour');
    

                    $basicSalary = $user->salary;
    
                    $totalOTPay = $otHoursForMonth * $otAllowanceValue;

                    $totalPayout = $basicSalary + $totalOTPay;

                    // Find or create a SalaryLog entry for the user and month
                    $salaryLog = SalaryLog::updateOrCreate(
                        [
                            'employee_id' => $employeeId,
                            'month' => $month,
                            'year' => date('Y'), // You can adjust this as needed
                        ],
                        [
                            'total_ot_hour' => $otHoursForMonth,
                            'total_ot_pay' => $totalOTPay,
                            'total_payout' => $totalPayout,
                        ]
                    );
                }
            }
        }
    
        // Retrieve the updated records
        $salaryLogs = SalaryLog::whereIn('employee_id', $users->pluck('id')->all())->get();
    
        return view('admin.salaryLogs', compact('salaryLogs', 'users'));
    }
    

}
