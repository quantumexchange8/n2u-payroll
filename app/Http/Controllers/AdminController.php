<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Duty;
use App\Models\Position;
use App\Models\PunchRecord;
use App\Models\PunchRecordLog;
use App\Models\SalaryLog;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\Task;
use App\Models\User;
use App\Models\Period;
use App\Models\OtApproval;
use App\Models\OtherImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
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
        $pendingOTCount2 = OtApproval::where('status', 'Pending')->count();

        // Get the current date
        $currentDate = Carbon::now()->toDateString();

        // Count total employees working for the current date
        $totalEmployeesCount = Schedule::whereDate('date', $currentDate)
                                        ->distinct('employee_id')
                                        ->count();

        // Retrieve all schedules, shifts, and settings
        $schedules = Schedule::all();
        $shifts = Shift::all();
        $settings = Setting::all();

        // Return the view with the punchRecords, schedules, shifts, and settings
        return view('admin.record', compact('punchRecords', 'schedules', 'shifts', 'settings', 'pendingOTCount', 'pendingOTCount2', 'totalEmployeesCount'));
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

        try{
            // Validate the incoming request data
            $validatedData = $request->validated();

            $full_name_with_underscores = str_replace(' ', '_', $validatedData['employee_id']);

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
                $filename = $full_name_with_underscores . '_account_pic.' . $extension; // Modify the file name
                $file->move('uploads/employee/accountPic/', $filename);
                $validatedData['account_pic'] = $filename;
            }

            // Handle other images
            if ($request->hasFile('other_image')) {
                $file = $request->file('other_image');
                $extension = $file->getClientOriginalExtension();
                $otherImageFilename = $full_name_with_underscores . '_other_image.' . time() . '.' . $extension;// Modify the file name
                $file->move('storage/employee/otherImage/', $otherImageFilename);

                // Storage::putFileAs('employee/otherImage', $file, $otherImageFilename);

                // Create the user record with the validated and modified data
                $user = User::create($validatedData);

                // Store the user ID for later use
                $userId = $user->id;

                // Insert data into other_images table
                OtherImage::create([
                    'employee_id' => $userId,
                    'file_name' => $otherImageFilename,
                ]);
            } else {
                // If 'other_image' is not present, create the user without creating an OtherImage record
                User::create($validatedData);
            }


            Alert::success('Done', 'Successfully Registered');
            return redirect()->route('viewEmployee');
        }catch(ValidationException $e){
            // If a validation exception occurs, redirect back with errors and input
            return redirect()->back()->withErrors($e->validator)->withInput();
        }

    }

    public function editEmployee($id) {
        $user = User::with('position')->find($id);
        $positions = Position::all(); // Fetch all positions

        //Fetch passport size photo, ic photo and offer letter
        $passport_size_photo = $user->passport_size_photo;
        $ic_photo = $user->ic_photo;
        $offer_letter = $user->offer_letter;
        $account_pic = $user->account_pic;

        return view('admin.editEmployee', compact('user', 'positions', 'passport_size_photo', 'ic_photo', 'offer_letter', 'account_pic'));
    }

    public function updateEmployee(EmployeeRequest $request, $id) {
        $data = User::find($id);

        // Validate the incoming request data
        $validatedData = $request->validated();

        $full_name_with_underscores = str_replace(' ', '_', $validatedData['employee_id']);

        // Handle file uploads if necessary
        $photoPath = 'uploads/employee/passportSizePhoto/';
        $icPath = 'uploads/employee/icPhoto/';
        $offerLetterPath = 'uploads/employee/offerLetter/';
        $accountPicPath = 'uploads/employee/accountPic/';
        // $otherImagePath = 'uploads/employee/otherImage/';

        // Handle passport size photo
        if ($request->hasFile('passport_size_photo')) {
            $photo = $request->file('passport_size_photo');
            $photoExtension = $photo->getClientOriginalExtension();
            $photoName = $full_name_with_underscores . '_photo.' . $photoExtension;

            // Delete all previous files with the same full name
            $filesToDelete = glob($photoPath . $full_name_with_underscores . '_photo.*');
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
            $icName = $full_name_with_underscores . '_ic.' . $icExtension;

            // Delete all previous files with the same full name
            $filesToDelete = glob($icPath . $full_name_with_underscores . '_ic.*');
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
            $offerLetterName = $full_name_with_underscores . '_offer_letter.' . $offerLetterExtension;

            // Delete all previous files with the same full name
            $filesToDelete = glob($offerLetterPath . $full_name_with_underscores . '_offer_letter.*');
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
            $accountPicName = $full_name_with_underscores . '_account_pic.' . $accountPicExtension;

            // Delete all previous files with the same full name
            $filesToDelete = glob($accountPicPath . $full_name_with_underscores . '_account_pic.*');
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

        // Handle other image
        // if ($request->hasFile('other_image')) {
        //     $otherImage = $request->file('other_image');
        //     $otherImageExtension = $otherImage->getClientOriginalExtension();
        //     $otherImageName = $full_name_with_underscores . '_other_image_' . time() . '.' . $otherImageExtension;

        //     // Upload the new offer letter
        //     $otherImage->move($otherImagePath, $otherImageName);

        //     // Update the database field with the new file name
        //     $validatedData['other_image'] = $otherImageName;

        //     // Insert data into other_images table
        //     OtherImage::create([
        //         'employee_id' => $validatedData['employee_id'],
        //         'file_name' => $otherImageName,
        //     ]);
        // }


        // Update the user's data based on the validated form input
        $data->update($validatedData);

        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('viewEmployee');

    }

    public function updateEmployeePassword(Request $request, $id){

        // Define validation rules
        $rules = [
            'new_password' => 'required',
        ];

        // Define custom error messages (optional)
        $messages = [
            'new_password.required' => 'The New Password field is required.',
        ];

            // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

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

        $employee->delete();

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

        // Define validation rules
        $rules = [
            'position_name' => 'required|max:255',
            'department_id' => 'required'
        ];

        $messages = [
            'position_name.required' => 'The Position Name field is required.',
            'position_name.max' => 'The Position Name should not exceed 255 characters.',
            'department_id.required' => 'The Department Name field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validation passed, proceed to save the new position
        $position = new Position();
        $position->position_name = $request->input('position_name');
        $position->department_id = $request->input('department_id');
        $position->save();

        Alert::success('Done', 'Successfully Inserted');
        return redirect()->route('viewPosition');
    }

    public function editPosition($id){
        $positions = Position::with('department')->find($id);
        $departments = Department::all();

        return view('admin.editPosition', compact('positions', 'departments'));
    }

    public function updatePosition(Request $request, $id){
        // Define validation rules
        $rules = [
            'position_name' => 'required|max:255',
        ];

        // Define custom error messages (optional)
        $messages = [
            'position_name.required' => 'The Position Name field is required.',
            'position_name.max' => 'The Position Name should not exceed 255 characters.',
        ];

         // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

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

        $position->delete();

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
        // Define validation rules
        $rules = [
            'department_name' => 'required|max:255',
        ];

        // Define custom error messages (optional)
        $messages = [
            'department_name.required' => 'The Department Name field is required.',
            'department_name.max' => 'The Department Name should not exceed 255 characters.',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validation passed, proceed to save the new department
        $department = new Department();
        $department->department_name = $request->input('department_name');
        $department->save();

        // Flash a success message for the next request
        Alert::success('Done', 'Successfully Inserted');

        return redirect()->route('viewDepartment');
    }

    public function editDepartment($id){
        $department = Department::find($id);

        return view('admin.editDepartment', ['department' => $department]);
    }

    public function updateDepartment(Request $request, $id){
        // Define validation rules
        $rules = [
            'department_name' => 'required|max:255',
        ];

        // Define custom error messages (optional)
        $messages = [
            'department_name.required' => 'The Department Name field is required.',
            'department_name.max' => 'The Department Name should not exceed 255 characters.',
        ];

            // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

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

        $department->delete();

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

        // Define validation rules
        $rules = [
            'duty_name' => 'required|max:255',
        ];

        // Define custom error messages (optional)
        $messages = [
            'duty_name.required' => 'The Duty Name field is required.',
            'duty_name.max' => 'The Duty Name should not exceed 255 characters.',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $duty = new Duty();
        $duty->duty_name = $request->input('duty_name');
        $duty->save();

        Alert::success('Done', 'Successfully Inserted');
        return redirect()->route('viewDuty');
    }

    public function editDuty($id){
        $duty = Duty::find($id);

        return view('admin.editDuty', ['duty' => $duty]);
    }

    public function updateDuty(Request $request, $id){
        // Define validation rules
        $rules = [
            'duty_name' => 'required|max:255',
        ];

        // Define custom error messages (optional)
        $messages = [
            'duty_name.required' => 'The Duty Name field is required.',
            'duty_name.max' => 'The Duty Name should not exceed 255 characters.',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

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

        $duty->delete();

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

        // Define validation rules
        $rules = [
            'shift_name' => 'required|max:255',
            'shift_start' => 'required',
            'shift_end' => 'required|after_or_equal:shift_start'
        ];

        // Define custom error messages (optional)
        $messages = [
            'shift_name.required' => 'The Shift Name field is required.',
            'shift_name.max' => 'The Shift Name should not exceed 255 characters.',
            'shift_start.required' => 'The Shift Start field is required.',
            'shift_end.required' => 'The Shift End field is required.',
            'shift_end.after_or_equal' => 'The end time must be after or equal to the start time.',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $shift = new Shift();
        $shift->shift_name = $request->input('shift_name');
        $shift->shift_start = $request->input('shift_start');
        $shift->shift_end = $request->input('shift_end');
        $shift->save();

        Alert::success('Done', 'Successfully Inserted');
        return redirect()->route('viewShift');
    }

    public function editShift($id){
        $shift = Shift::find($id);

        return view('admin.editShift', ['shift' => $shift]);
    }

    public function updateShift(Request $request, $id){
        // Define validation rules
        $rules = [
            'shift_name' => 'required|max:255',
            'shift_start' => 'required',
            'shift_end' => 'required|after_or_equal:shift_start',
        ];

        // Define custom error messages (optional)
        $messages = [
            'shift_name.required' => 'The Shift Name field is required.',
            'shift_name.max' => 'The Shift Name should not exceed 255 characters.',
            'shift_start.required' => 'The Shift Start is required.',
            'shift_end.required' => 'The Shift End is required.',
            'shift_end.after_or_equal' => 'The end time must be after or equal to the start time.',
        ];

            // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

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

        $shift->delete();

        Alert::success('Done', 'Successfully Deleted');
        return redirect()->route('viewShift');
    }

    public function schedule(Request $request){
        $date = $request->input('date');
        // Check if the request wants JSON data
        if ($request->wantsJson()) {
            // Retrieve and return joined data for FullCalendar
            $joinedData = Schedule::leftJoin('users', 'schedules.employee_id', '=', 'users.id')
                ->leftJoin('shifts', 'schedules.shift_id', '=', 'shifts.id')
                ->select('schedules.id', 'schedules.employee_id', 'users.full_name',
                        'shifts.shift_start', 'shifts.shift_end', 'schedules.date',
                        'schedules.remarks', 'schedules.off_day', 'users.nickname')
                ->get();

            return response()->json($joinedData);
        }

        // If it's not a JSON request, return the view for the schedule page
        $schedules = Schedule::all();
        $users = User::where('role', 'member')->with('position')->get();
        $shifts = Shift::all();

        return view('admin.schedule', compact('schedules', 'users', 'shifts'));
    }

    public function scheduleReport(){
        $schedules = Schedule::orderBy('date', 'asc')->get();
        $users = User::where('role', 'member')->with('position')->get();
        $shifts = Shift::all();
        $periods = Period::all();

        foreach ($schedules as $schedule) {
            // Retrieve tasks related to the current schedule
            $tasks = Task::where('date', $schedule->date)
                    ->where('employee_id', $schedule->employee_id)
                    ->with('duty')
                    ->with('period')
                    ->get();

            // Attach the tasks to the schedule object
            $schedule->tasks = $tasks;
        }

        return view('admin.scheduleReport', compact('schedules', 'users', 'shifts', 'periods'));
    }

    public function getSchedule(Request $request) {
        try {
            $date = $request->input('date');

            $schedule = Schedule::leftJoin('users', 'schedules.employee_id', '=', 'users.id')
                    ->leftJoin('shifts', 'schedules.shift_id', '=', 'shifts.id')
                    ->select('schedules.id', 'schedules.employee_id', 'users.nickname',
                        'shifts.shift_start', 'shifts.shift_end', 'schedules.remarks')
                    ->where('schedules.date', $date)
                    ->get();

            return response()->json($schedule);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createSchedule(){
        $schedules = Schedule::all();
        $users = User::where('role', 'member')->with('position')->get();
        $shifts = Shift::all();
        $duties = Duty::all();
        $periods = Period::all();

        return view('admin.createSchedule', compact('schedules', 'users', 'shifts', 'duties', 'periods'));
    }

    // public function addSchedule(Request $request){

    //     $data = $request->all();

    //     if (isset($data['off_day']) && $data['off_day'] == 1) {
    //         // Perform actions for off_day being 1 (e.g., save to database)
    //         foreach ($data['selected_users'] as $userId) {
    //             $start = Carbon::parse($data['date_start']);
    //             $end = Carbon::parse($data['date_end']);

    //             // Loop through dates and save schedule for each date
    //             while ($start->lte($end)) {
    //                 $schedule = new Schedule();
    //                 $schedule->employee_id = $userId;
    //                 $schedule->date = $start->toDateString();
    //                 $schedule->off_day = 1;
    //                 $schedule->shift_id = null;
    //                 $schedule->remarks = null;
    //                 $schedule->save();

    //                 $start->addDay();
    //             }
    //         }

    //         // Redirect to the schedule page
    //         Alert::success('Done', 'Successfully Inserted');
    //         return redirect()->route('schedule');
    //     }

    //     $validationPassed = true;

    //     // Validation
    //     $validator = Validator::make($data, [
    //         'date_start' => 'required|date',
    //         'date_end' => 'nullable|date|after_or_equal:date_start',
    //         'shift_id' => 'nullable',
    //         'remarks' => 'nullable',
    //         'off_day' => 'nullable',
    //         'selected_users' => 'required|array',
    //     ], [
    //         'date_start.required' => 'The start date is required.',
    //         'date_start.date' => 'The start date must be a valid date.',
    //         'date_end.date' => 'The end date must be a valid date.',
    //         'date_end.after_or_equal' => 'The end date must be after or equal to the start date.',
    //         'shift_id.nullable' => 'The shift ID can be nullable.',
    //         'selected_users.required' => 'Please select at least one user.',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     foreach ($data['selected_users'] as $key => $userId) {
    //         // Your logic for off_day being 0
    //         $start = Carbon::parse($data['date_start']);
    //         $end = Carbon::parse($data['date_end']);
    //         $dates = [];

    //         if ($data['date_end'] === null) {
    //             $dates[] = $start->toDateString();
    //         } else {
    //             while ($start->lte($end)) {
    //                 $dates[] = $start->toDateString();
    //                 $start->addDay();
    //             }
    //         }

    //         foreach ($dates as $date) {
    //             // Check if a record already exists for the user and date
    //             $existingSchedule = Schedule::where('employee_id', $userId)
    //                                 ->where('date', $date)
    //                                 ->first();

    //             if (isset($data['period_id'][$key]) && !$this->validateDuplicatePeriodIds($userId, $date, $data['period_id'][$key])) {
    //                 Alert::error('Error', 'One employee cannot have duplicate period IDs in a day.');
    //                 return redirect()->route('schedule');
    //             }

    //             if ($existingSchedule) {
    //                 $userNickname = User::find($userId)->nickname; // Assuming User is the model for your user table
    //                 Alert::error('Error', 'Record for ' . $date . ' already exists for user ' . $userNickname);
    //                 return redirect()->route('schedule');
    //             }

    //             $schedule = new Schedule();
    //             $schedule->date = $date;
    //             $schedule->employee_id = $userId;
    //             $schedule->off_day = 0;
    //             $schedule->shift_id = $data['shift_id'];
    //             $schedule->remarks = $data['remarks'];
    //             $schedule->save();

    //             // Check if the save operation was successful
    //             if (!$schedule->exists) {
    //                 $validationPassed = false;
    //                 break;
    //             }

    //         }
    //     }

    //     // Display success alert
    //     if ($validationPassed) {
    //         Alert::success('Done', 'Successfully Inserted');
    //         return redirect()->route('schedule');
    //     }

    //     // Check if 'group-a' is present in the request, indicating task entries
    //     if ($request->has('group-a') && !empty($request->input('group-a'))) {
    //         $this->addTask($request, $dates);
    //     }
    // }

    // public function addSchedule(Request $request){

    //     $data = $request->all();

    //     if (isset($data['off_day']) && $data['off_day'] == 1) {
    //         // Perform actions for off_day being 1 (e.g., save to database)
    //         foreach ($data['selected_users'] as $userId) {
    //             $start = Carbon::parse($data['date_start']);
    //             $end = Carbon::parse($data['date_end']);

    //             // Loop through dates and save schedule for each date
    //             while ($start->lte($end)) {
    //                 $schedule = new Schedule();
    //                 $schedule->employee_id = $userId;
    //                 $schedule->date = $start->toDateString();
    //                 $schedule->off_day = 1;
    //                 $schedule->shift_id = null;
    //                 $schedule->remarks = null;
    //                 $schedule->save();

    //                 $start->addDay();
    //             }
    //         }

    //         // Redirect to the schedule page
    //         Alert::success('Done', 'Successfully Inserted');
    //         return redirect()->route('schedule');
    //     }

    //     $validationPassed = true;

    //     // Validation
    //     $validator = Validator::make($data, [
    //         'date_start' => 'required|date',
    //         'date_end' => 'nullable|date|after_or_equal:date_start',
    //         'shift_id' => 'nullable',
    //         'remarks' => 'nullable',
    //         'off_day' => 'nullable',
    //         'selected_users' => 'required|array',
    //     ], [
    //         'date_start.required' => 'The start date is required.',
    //         'date_start.date' => 'The start date must be a valid date.',
    //         'date_end.date' => 'The end date must be a valid date.',
    //         'date_end.after_or_equal' => 'The end date must be after or equal to the start date.',
    //         'shift_id.nullable' => 'The shift ID can be nullable.',
    //         'selected_users.required' => 'Please select at least one user.',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     // foreach ($data['selected_users'] as $key => $userId) {
    //     //     // Your logic for off_day being 0
    //     //     $start = Carbon::parse($data['date_start']);
    //     //     $end = Carbon::parse($data['date_end']);
    //     //     $dates = [];

    //     //     if ($data['date_end'] === null) {
    //     //         $dates[] = $start->toDateString();
    //     //     } else {
    //     //         while ($start->lte($end)) {
    //     //             $dates[] = $start->toDateString();
    //     //             $start->addDay();
    //     //         }
    //     //     }

    //     //     foreach ($dates as $date) {
    //     //         // Check if a record already exists for the user and date
    //     //         $existingSchedule = Schedule::where('employee_id', $userId)
    //     //                             ->where('date', $date)
    //     //                             ->first();

    //     //         if (isset($data['period_id'][$key]) && !$this->validateDuplicatePeriodIds($userId, $date, $data['period_id'][$key])) {
    //     //             Alert::error('Error', 'One employee cannot have duplicate period IDs in a day.');
    //     //             return redirect()->route('schedule');
    //     //         }

    //     //         if ($existingSchedule) {
    //     //             $userNickname = User::find($userId)->nickname; // Assuming User is the model for your user table
    //     //             Alert::error('Error', 'Record for ' . $date . ' already exists for user ' . $userNickname);
    //     //             return redirect()->route('schedule');
    //     //         }

    //     //         $schedule = new Schedule();
    //     //         $schedule->date = $date;
    //     //         $schedule->employee_id = $userId;
    //     //         $schedule->off_day = 0;
    //     //         $schedule->shift_id = $data['shift_id'];
    //     //         $schedule->remarks = $data['remarks'];
    //     //         $schedule->save();

    //     //         // Check if the save operation was successful
    //     //         if (!$schedule->exists) {
    //     //             $validationPassed = false;
    //     //             break;
    //     //         }

    //     //     }
    //     // }

    //     // Display success alert


    //     $successMessages = [];
    //     $failedMessages = [];

    //     foreach ($data['selected_users'] as $key => $userId) {
    //         // Your logic for off_day being 0
    //         $start = Carbon::parse($data['date_start']);
    //         $end = Carbon::parse($data['date_end']);
    //         $dates = [];

    //         if ($data['date_end'] === null) {
    //             $dates[] = $start->toDateString();
    //         } else {
    //             while ($start->lte($end)) {
    //                 $dates[] = $start->toDateString();
    //                 $start->addDay();
    //             }
    //         }

    //         $successInsertedDates = [];
    //         foreach ($dates as $date) {


    //             // Check if a record already exists for the user and date
    //             $existingSchedule = Schedule::where('employee_id', $userId)
    //                 ->where('date', $date)
    //                 ->first();

    //             $userNickname = User::find($userId)->nickname;

    //             if (isset($data['period_id'][$key]) && !$this->validateDuplicatePeriodIds($userId, $date, $data['period_id'][$key])) {
    //                 Alert::error('Error', 'One employee cannot have duplicate period IDs in a day.');
    //                 return redirect()->route('schedule');
    //             }

    //             if ($existingSchedule) {
    //                 $failedMessages[] = "Fail to insert $date for user $userNickname due to existing.";
    //                 continue; // Skip to the next iteration if a record already exists for the current date
    //             }

    //             $schedule = new Schedule();
    //             $schedule->date = $date;
    //             $schedule->employee_id = $userId;
    //             $schedule->off_day = 0;
    //             $schedule->shift_id = $data['shift_id'];
    //             $schedule->remarks = $data['remarks'];
    //             $schedule->save();



    //             // Check if the save operation was successful
    //             if ($schedule->exists) {
    //                 $successInsertedDates[] = $date;
    //             } else {
    //                 $validationPassed = false;
    //                 $failedMessages[] = "Failed to insert record for user $userId on $date.";
    //             }
    //         }

    //         if (!empty($successInsertedDates)) {
    //             $successMessages[] = "Record for $userNickname on " . implode(' and ', $successInsertedDates) . " successfully inserted.";
    //         }
    //     }

    //     // Display success alert or failure message
    //     if ($validationPassed) {
    //         Alert::success('Done', 'Successfully Inserted');
    //     } else {
    //         Alert::error('Error', 'Some insertions failed. See details below.');
    //         foreach ($failedMessages as $message) {
    //             Alert::error('Error Detail', $message);
    //         }
    //     }

    //     // Display messages for successful insertions
    //     foreach ($successMessages as $message) {
    //         Alert::success('Success Detail', $message);
    //     }

    //     return redirect()->route('schedule');

    //     // Check if 'group-a' is present in the request, indicating task entries
    //     if ($request->has('group-a') && !empty($request->input('group-a'))) {
    //         $this->addTask($request, $dates);
    //     }
    // }

    public function addSchedule(Request $request){

        $data = $request->all();

        // Validation
        $validator = Validator::make($data, [
            'date_start' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'shift_id' => 'nullable',
            'remarks' => 'nullable',
            'selected_users' => 'required|array',
        ], [
            'date_start.required' => 'The Start Date is required.',
            'date_start.date' => 'The Start Date must be a valid date.',
            'date_end.date' => 'The End Date must be a valid date.',
            'date_end.after_or_equal' => 'The End Date must be after or equal to the start date.',
            'shift_id.nullable' => 'The Shift ID can be nullable.',
            'selected_users.required' => 'Please select at least one user.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (isset($data['off_day']) && $data['off_day'] == 1) {

            // Perform actions for off_day being 1 (e.g., save to database)
            foreach ($data['selected_users'] as $userId) {
                $start = Carbon::parse($data['date_start']);
                $end = Carbon::parse($data['date_end']);

                // Loop through dates and save schedule for each date
                // while ($start->lte($end)) {
                    $this->saveSchedule($userId, $start, true);
                    $start->addDay();

                // }
            }

            // Redirect to the schedule page
            Alert::success('Done', 'Successfully Inserted');
            return redirect()->route('schedule');
        }

        $successMessages = [];
        $failedMessages = [];

        $selectedUsers = $data['selected_users'];

        foreach ($data['selected_users'] as $key => $userId) {
            // Your logic for off_day being 0
            $start = Carbon::parse($data['date_start']);
            $end = Carbon::parse($data['date_end']);
            $dates = [];

            if ($data['date_end'] === null) {
                $dates[] = $start->toDateString();
            } else {
                while ($start->lte($end)) {
                    $dates[] = $start->toDateString();
                    $start->addDay();
                }
            }

            $successInsertedDates = [];
            $failedDates = [];

            foreach ($dates as $date) {
                $result = $this->saveSchedule($userId, $date);

                if ($result === true) {
                    $formattedDate = \Carbon\Carbon::parse($date)->format('d M Y'); // Format the date
                    $successInsertedDates[] = $formattedDate;
                } elseif ($result === 'max_schedules_exceeded') {
                    $userNickname = User::find($userId)->nickname;
                    $formattedFailedDate = \Carbon\Carbon::parse($date)->format('d M Y'); // Format the date
                    $failedMessages[] = "Failed to insert $formattedFailedDate for user $userNickname due to exceeding the maximum of two schedules.";
                    $failedDates[] = $formattedFailedDate;
                } elseif ($result === 'shift_overlap') {
                    $userNickname = User::find($userId)->nickname;
                    $formattedFailedDate = \Carbon\Carbon::parse($date)->format('d M Y'); // Format the date
                    $failedMessages[] = "Failed to insert $formattedFailedDate for user $userNickname due to shift overlap.";
                    $failedDates[] = $formattedFailedDate;
                }
            }

            if (!empty($successInsertedDates)) {
                $userNickname = User::find($userId)->nickname;
                $successMessages[] = "Schedule for user $userNickname on " . implode(' and ', $successInsertedDates) . " successfully inserted.";
            }


            if (!empty($successInsertedDates)) {
                $userNickname = User::find($userId)->nickname;
                $successMessages[] = "Schedule for user $userNickname on " . implode(' and ', $successInsertedDates) . " successfully inserted.";
            }

            // if (!empty($failedDates)) {
            //     $userNickname = User::find($userId)->nickname;
            //     $failedMessages[] = "Failed to insert " . implode(' and ', $failedDates) . " for user $userNickname due to exceeding the maximum of two schedules.";
            // }
        }

        // Display messages for successful insertions
        foreach ($successMessages as $message) {
            Alert::success('Success Detail', $message);
        }

        // Display messages for failed insertions
        if (!empty($failedMessages)) {
            Alert::error('Error', 'Some insertions failed. See details below.');
            foreach ($failedMessages as $message) {
                Alert::error('Error Detail', $message);
            }
        }

        // Check if 'group-a' is present in the request, indicating task entries
        // if ($request->has('group-a') && !empty($request->input('group-a'))) {
        //     $this->saveTask($request, $dates, $selectedUsers);
        // }

        // if ($request->has('group-a') && is_array($request->input('group-a')) && count(array_filter($request->input('group-a'))) > 0) {
        //     $this->saveTask($request, $dates, $selectedUsers);
        // }

        if ($request->has('group-a') && $this->hasNonEmptyValues($request->input('group-a'))) {
            $this->saveTask($request, $dates, $selectedUsers);
        }

        return redirect()->route('schedule');
    }

    private function hasNonEmptyValues($array){
        foreach ($array as $item) {
            if (!is_array($item) || count(array_filter($item, function ($value) {
                return !is_null($value);
            })) > 0) {
                return true;
            }
        }

        return false;
    }

    private function saveSchedule($userId, $date, $offDay = false){
        // Check if there are already two schedule records for the same user and date
        $existingSchedules = DB::table('schedules')
            ->where('employee_id', $userId)
            ->where('date', $date)
            ->whereNull('deleted_at')
            ->count();

        // Check if the user already has two schedules on this date
        if ($existingSchedules >= 2) {
            // Display an error message for exceeding two schedules
            return 'max_schedules_exceeded';
        }

        // If it's not an off day, check for shift overlap
        if (!$offDay) {
            $shiftId = request('shift_id');

            // Retrieve shift details based on the selected shift_id
            $shiftDetails = Shift::where('id', $shiftId)->first();

            // Check if the shift details are found
            if (!$shiftDetails) {
                // Display an error message for invalid shift_id
                return 'invalid_shift';
            }

            $newShiftStart = $shiftDetails->shift_start;
            $newShiftEnd = $shiftDetails->shift_end;

            // Retrieve all existing shifts for the same user and date
            $existingShifts = DB::table('schedules')
                ->join('users', 'schedules.employee_id', '=', 'users.id')
                ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')
                ->where('schedules.employee_id', $userId)
                ->where('schedules.date', $date)
                ->whereNull('schedules.deleted_at')
                ->select('users.employee_id', 'shifts.shift_start', 'shifts.shift_end')
                ->get();

            // Check for overlap with each existing shift
            foreach ($existingShifts as $existingShift) {
                $existingShiftStart = $existingShift->shift_start;
                $existingShiftEnd = $existingShift->shift_end;

                // Check if the new shift overlaps with the existing shift
                if ($newShiftStart < $existingShiftEnd && $newShiftEnd > $existingShiftStart) {
                    // Display an error message for shift overlap
                    return 'shift_overlap';
                }
            }
        }

        // Your logic for creating and saving a new schedule
        $schedule = new Schedule();
        $schedule->date = $date;
        $schedule->employee_id = $userId;
        $schedule->off_day = $offDay ? 1 : 0;
        $schedule->shift_id = request('shift_id'); // You may need to replace this with your actual logic
        $schedule->remarks = request('remarks'); // You may need to replace this with your actual logic
        $schedule->save();

        return true;
    }

    private function saveTask($data, $dates, $selectedUsers){
        $successMessages = [];
        $failedMessages = [];

        foreach ($selectedUsers as $key => $userId) {
            // Your logic for off_day being 0
            $taskDates = [];

            if ($data['date_end'] === null) {
                $taskDates[] = $data['date_start'];
            } else {
                $taskDates = $dates;
            }

            $successInsertedTaskDates = [];
            $failedTaskDates = [];

            foreach ($taskDates as $date) {
                if ($this->saveTaskEntry($userId, $date, $data['group-a'])) {
                    $successInsertedTaskDates[] = $date;
                } else {
                    $failedTaskDates[] = $date;
                }
            }

            if (!empty($failedTaskDates)) {
                $userNickname = User::find($userId)->nickname;
                $failedMessages[] = "Failed to insert task for " . implode(' and ', $failedTaskDates) . " for user $userNickname.";
            }

            if (!empty($successInsertedTaskDates)) {
                $userNickname = User::find($userId)->nickname;
                $successMessages[] = "Task for user $userNickname on " . implode(' and ', $successInsertedTaskDates) . " successfully inserted.";
            }
        }
    }

    private function saveTaskEntry($userId, $date, $groupAData){
        $success = true;

        foreach ($groupAData as $taskData) {
            $task = new Task();
            $task->employee_id = $userId;
            $task->date = $date;
            $task->period_id = $taskData['period_id'];
            $task->start_time = $taskData['start_time'];
            $task->end_time = $taskData['end_time'];
            $task->duty_id = $taskData['duty_id'];
            $task->save();

            // If any task insertion fails, set $success to false
            if (!$task->exists) {
                $success = false;
            }
        }

        return $success;
    }

    public function editSchedule($id){
        $schedule = Schedule::find($id);
        $users = User::where('role', 'member')->with('position')->get();
        $shifts = Shift::all();
        $duties = Duty::all();
        $periods = Period::all();

        // Retrieve tasks and duties based on the schedule's employee ID and date
        $tasksAndDuties = $this->getTasksAndDuties($schedule->employee_id, $schedule->date);

        return view('admin.editSchedule', compact('schedule', 'users', 'shifts', 'tasksAndDuties', 'duties', 'periods'));
    }

    // Function to get tasks and duties based on employee ID and date
    private function getTasksAndDuties($employeeId, $date){
        // Use the query to get tasks and duties
        $tasksAndDuties = DB::table('tasks')
            ->join('duties', 'tasks.duty_id', '=', 'duties.id')
            ->join('periods', 'tasks.period_id', '=', 'periods.id')
            ->where('tasks.employee_id', $employeeId)
            ->where('tasks.date', $date)
            ->whereNull('tasks.deleted_at')
            ->get();

        return $tasksAndDuties;
    }

    // public function updateSchedule(Request $request, $id){
    //     // dd($request->all());
    //     // $data = Schedule::find($id);

    //     $data = Schedule::with('tasks') // Load the tasks relationship
    //     ->find($id);

    //     $data->update([
    //         'employee_id' => $request->input('employee_id'),
    //         'shift_id' => $request->input('shift_id'),
    //         'date' => $request->input('date'),
    //         'remarks' =>$request->input('remarks'),
    //         // 'off_day' => $request->off_day,
    //     ]);

    //     foreach ($data->tasks as $task) {
    //         $this->updateTask($request, $task->id);
    //     }

    //     Alert::success('Done', 'Successfully Updated');
    //     return redirect()->route('schedule');
    // }

    public function updateSchedule(Request $request, $id){

        // dd($request->all());
        // Validate the request data
        $request->validate([
            // Validation rules for your form fields
        ]);

        // Find the schedule by ID
        $schedule = Schedule::find($id);

        // Update schedule details
        $schedule->employee_id = $request->input('employee_id');
        $schedule->date = $request->input('date');
        $schedule->shift_id = $request->input('shift_id');
        $schedule->remarks = $request->input('remarks');

        // Save the updated schedule
        $schedule->save();

        // Check if there are tasks and duties submitted in the request
        $tasksAndDuties = $request->input('group-a', []);

        // If there are tasks and duties, update or insert them into the tasks table
        if (!empty($tasksAndDuties)) {
            foreach ($tasksAndDuties as $taskData) {
                // Check if all key fields are null
                $fieldsNull = is_null($taskData['period_id']) ||
                                    is_null($taskData['duty_id']) ||
                                    is_null($taskData['start_time']) ||
                                    is_null($taskData['end_time']);

                // If all key fields are not null, proceed with creating/updating the task
                if (!$fieldsNull) {
                    // Check if a task exists with the given conditions
                    $existingTask = Task::where([
                        'date' => $schedule->date,
                        'employee_id' => $schedule->employee_id,
                        'period_id' => $taskData['period_id'],
                    ])->first();

                    if ($existingTask) {
                        // Print debug information
                        // dd('Task exists. Update it:', $existingTask->toArray());

                        // Update existing task
                        $existingTask->update([
                            'start_time' => $taskData['start_time'],
                            'end_time' => $taskData['end_time'],
                            'duty_id' => $taskData['duty_id'],
                        ]);
                    } else {
                        // Find or create a task based on date, employee_id, and task_name
                        $task = Task::Create(
                            [
                                'date' => $schedule->date,
                                'employee_id' => $schedule->employee_id,
                                'period_id' => $taskData['period_id'],
                                'start_time' => $taskData['start_time'],
                                'end_time' => $taskData['end_time'],
                                'duty_id' => $taskData['duty_id'],
                            ]
                        );
                    }
                }
            }
        }

        // Redirect with success message
        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('schedule');
    }

    public function deleteSchedule($id){

        $schedule = Schedule::find($id);
        if ($schedule) {
            $schedule->delete();
            Alert::success('Done', 'Successfully Deleted');
            return response()->json(['message' => 'Schedule deleted successfully']);
        } else {
            return response()->json(['message' => 'Schedule not found'], 404);
        }
    }

    public function deleteSchedule2($id){

        $schedule = Schedule::find($id);

        $schedule->delete();

        Alert::success('Done', 'Successfully Deleted');
        return redirect()->route('scheduleReport');
    }

    public function duplicateSchedule(Request $request){

        try {
            $selectedUserId = $request->input('selectedUserId');
            $filteredRows = $request->input('filteredRows');
            $allScheduleData = [];  // Array to store data for all selected rows

            // Fetch associated data for each scheduleId
            foreach ($filteredRows as $row) {
                $scheduleId = $row['scheduleId'];

                // Fetch associated data for the current scheduleId
                $scheduleData = Schedule::find($scheduleId);

                // Check if a schedule already exists for the selected user and date
                $existingSchedule = Schedule::where('employee_id', $selectedUserId)
                        ->where('date', $scheduleData->date)
                        ->whereNull('deleted_at')
                        ->count();

                if ($existingSchedule < 2) {

                    // Retrieve shift details based on the selected shift_id
                    $shiftDetails = Shift::where('id', $scheduleData->shift_id)->first();

                    $newShiftStart = $shiftDetails->shift_start;
                    $newShiftEnd = $shiftDetails->shift_end;

                    // Retrieve all existing shifts for the same user and date
                    $existingShifts = DB::table('schedules')
                                        ->join('users', 'schedules.employee_id', '=', 'users.id')
                                        ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')
                                        ->where('schedules.employee_id', $selectedUserId)
                                        ->where('schedules.date', $scheduleData->date)
                                        ->whereNull('schedules.deleted_at')
                                        ->select('users.employee_id', 'shifts.shift_start', 'shifts.shift_end')
                                        ->get();

                    // Check for overlap with each existing shift
                    foreach ($existingShifts as $existingShift) {
                        $existingShiftStart = $existingShift->shift_start;
                        $existingShiftEnd = $existingShift->shift_end;

                        // Check if the new shift overlaps with the existing shift
                        if ($newShiftStart < $existingShiftEnd && $newShiftEnd > $existingShiftStart) {
                            // Display an error message for shift overlap
                            $errorMessage = 'Failed to duplicate due to shift overlap.';
                            return response()->json(['error' => $errorMessage], 422);
                        } else {
                            // Extract specific fields from the scheduleData
                            $selectedScheduleData = [
                                'id' => $scheduleData->id,
                                'date' => $scheduleData->date,
                                'employee_id' => $selectedUserId,
                                'shift_id' => $scheduleData->shift_id,
                                'off_day' => $scheduleData->off_day,
                            ];

                            // Create a new Schedule model and save it to the database
                            $newSchedule = Schedule::create($selectedScheduleData);

                            // Check if 'tasks' key exists in the current row
                            if (isset($row['tasks']) && is_array($row['tasks'])) {
                                // Extract task data from the tasks array
                                $tasksData = [];
                                foreach ($row['tasks'] as $task) {
                                    $existingTask = Task::where('employee_id', $selectedUserId)
                                                        ->where('period_id', $task['period_id'])
                                                        ->where('date', $task['date'])
                                                        ->whereNull('deleted_at')
                                                        ->first();

                                    // Only create a new task if it doesn't already exist
                                    if (!$existingTask) {
                                        // Create a new Task model associated with the new Schedule
                                        $newSchedule->tasks()->create([
                                            'date' => $task['date'],
                                            'employee_id' => $selectedUserId,
                                            'period_id' => $task['period_id'],
                                            'duty_id' => $task['duty_id'],
                                            'start_time' => $task['start_time'],
                                            'end_time' => $task['end_time']
                                        ]);
                                    }
                                }
                            }
                            return response()->json(['message' => 'Successfully duplicated'], 200);
                        }
                    }
                }
                else {
                    $errorMessage = 'Failed to duplicate due to exceeding the maximum of two schedules.';
                    return response()->json(['error' => $errorMessage], 422);
                }
            }

        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            $errorMessage = 'Failed to duplicate due to an unexpected error.';
            return response()->json(['error' => $errorMessage], 500);
        }

    }

    public function viewPeriod(){
        $periods = Period::all();

        return view('admin.viewPeriod', ['periods' => $periods]);
    }

    public function createPeriod(){

        return view('admin.createPeriod');
    }

    public function addPeriod(Request $request){

        // Define validation rules
        $rules = [
            'period_name' => 'required|max:255',
        ];

        // Define custom error messages (optional)
        $messages = [
            'period_name.required' => 'The Period Name field is required.',
            'period_name.max' => 'The Period Name should not exceed 255 characters.',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $period = new Period();
        $period->period_name = $request->input('period_name');
        $period->save();

        Alert::success('Done', 'Successfully Inserted');
        return redirect()->route('viewPeriod');
    }

    public function editPeriod($id){
        $period = Period::find($id);

        return view('admin.editPeriod', ['period' => $period]);
    }

    public function updatePeriod(Request $request, $id){
        // Define validation rules
        $rules = [
            'period_name' => 'required|max:255',
        ];

        // Define custom error messages (optional)
        $messages = [
            'period_name.required' => 'The Period Name field is required.',
            'period_name.max' => 'The Period Name should not exceed 255 characters.',
        ];

            // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = Period::find($id);

        //Update the user's data based on the form input
        $data->update([
            'period_name' => $request->input('period_name')
        ]);

        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('viewPeriod');
    }

    public function deletePeriod($id){

        $period = Period::find($id);

        $period->delete();

        Alert::success('Done', 'Successfully Deleted');
        return redirect()->route('viewPeriod');
    }

    public function viewTask(){
        $tasks = Task::orderBy('date', 'asc')->get();
        $users = User::all();
        $duty = Duty::all();
        $periods = Period::all();

        return view('admin.viewTask', [
            'tasks' => $tasks,
            'users' => $users,
            'duty' => $duty,
            'periods' => $periods,
        ]);
    }

    public function createTask(){
        $users = User::where('role', 'member')->with('position')->get();
        $duties = Duty::all();
        $periods = Period::all();

        return view('admin.createTask', compact('users', 'duties', 'periods'));
    }

    private function taskExists($userId, $date, $periodId){
        return Task::where('employee_id', $userId)
                    ->where('date', $date)
                    ->where('period_id', $periodId)
                    ->exists();
    }

    public function addTask(Request $request, $dates = null, $selectedUsers = null) {

        // If $dates and $selectedUsers are not provided, use request input
        if ($dates === null) {
            $dates = $request->input('dates', []);
        }

        if ($selectedUsers === null) {
            $selectedUsers = $request->input('selected_users', []);
        }

        // If $dates is a string, convert it to an array
        if (!is_array($dates)) {
            $dates = explode(',', $dates);
        }

        // Define validation rules
        $rules = [
            'group-a' => 'required|array', // Ensure at least one task is submitted
            'group-a.*.period_id' => 'required',
            'group-a.*.start_time' => 'required',
            'group-a.*.end_time' => 'required',
            'group-a.*.duty_id' => 'required',
            'selected_users' => 'required|array|min:1',
            'selected_users.*' => 'exists:users,id',
            'dates' => 'required',
        ];

        // Define custom error messages (optional)
        $messages = [
            'group-a.*.period_id.required' => 'The Period Name field is required.',
            'group-a.*.start_time.required' => 'The Start Time field is required.',
            'group-a.*.end_time.required' => 'The End Time field is required.',
            'group-a.*.duty_id.required' => 'The Duty Name field is required.',
            'selected_users.required' => 'Please select at least one user.',
            'selected_users.*.exists' => 'Invalid user selected.',
            'dates.required' => 'Please select one date.',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $tasks = $request->input('group-a', []);

        // Loop through selected users and create tasks
        foreach ($selectedUsers as $userId) {

            foreach ($tasks as $taskData) {
                foreach ($dates as $date) {

                    $periodId = $taskData['period_id'];

                    // Check if the task already exists
                    if ($this->taskExists($userId, $date, $periodId)) {
                        Alert::error('Failed', 'Task already exists for this user, date, and period.');
                        return redirect()->route('viewTask');
                    }

                    $task = new Task();
                    $task->period_id = $taskData['period_id'];
                    $task->date = $date;
                    $task->start_time = $taskData['start_time'];
                    $task->end_time = $taskData['end_time'];
                    $task->duty_id = $taskData['duty_id'];
                    $task->employee_id = $userId;
                    // Check if the task is saved successfully
                    if (!$task->save()) {
                        // Log the error
                        Log::error('Failed to insert task:', ['data' => $taskData, 'error' => $task->getError()]);
                        // If not, display an error message and redirect
                        Alert::error('Error', 'Failed to insert tasks.')->autoClose(5000);
                        return response()->json(['status' => 'error', 'message' => 'Failed to insert tasks.']);
                    }
                }
            }
        }

        Alert::success('Done', 'Successfully Inserted');
        return redirect()->route('viewTask');
        // return response()->json(['status' => 'success', 'message' => 'Successfully Inserted.']);
    }

    public function editTask($id){
        $task = Task::find($id);
        $users = User::all();
        $duties = Duty::all();
        $periods = Period::all();

        return view('admin.editTask', [
            'task' => $task,
            'users' => $users,
            'duties' => $duties,
            'periods' => $periods,
        ]);
    }

    public function updateTask(Request $request, $id){
        // Define validation rules
        $rules = [
            'employee_id' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'duty_id' => 'required',
            'period_id' => 'required',
        ];

        // Define custom error messages (optional)
        $messages = [
            'employee_id.required' => 'Nickname is required.',
            'date.required' => 'Date is required.',
            'start_time.required' => 'Start Time is required.',
            'end_time.required' => 'End Time is required.',
            'duty_id.required' => 'Duty is required.',
            'period_id.required' => 'Period is required.',
        ];

            // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = Task::find($id);

        //Update the user's data based on the form input
        $data->update([
            'task_name' => $request->input('task_name'),
            'employee_id' => $request->input('employee_id'),
            'date' => $request->input('date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'duty_id' => $request->input('duty_id'),
            'period_id' => $request->input('period_id'),
        ]);

        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('viewTask');
    }

    // public function updateTask(Request $request, $id){

    //     // Define validation rules
    //     $rules = [
    //         'employee_id' => 'required',
    //         'date' => 'required',
    //         'start_time' => 'required',
    //         'end_time' => 'required',
    //         'duty_id' => 'required',
    //     ];

    //     // Define custom error messages (optional)
    //     $messages = [
    //         'employee_id.required' => 'Nickname is required.',
    //         'date.required' => 'Date is required.',
    //         'start_time.required' => 'Start Time is required.',
    //         'end_time.required' => 'End Time is required.',
    //         'duty_id.required' => 'Duty Name is required.',
    //     ];

    //     // Validate the request data
    //     $validator = Validator::make($request->all(), $rules, $messages);

    //     if ($validator->fails()) {
    //         return redirect()
    //             ->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     // Find the task by ID
    //     $task = Task::find($id);

    //     if ($task) {
    //         // Update existing task
    //         $task->update([
    //             'task_name' => $request->input('task_name'),
    //             'employee_id' => $request->input('employee_id'),
    //             'date' => $request->input('date'),
    //             'start_time' => $request->input('start_time'),
    //             'end_time' => $request->input('end_time'),
    //             'duty_id' => $request->input('duty_id'),
    //         ]);
    //     } else {
    //         // Insert new task
    //         $newTask = new Task([
    //             'task_name' => $request->input('task_name'),
    //             'employee_id' => $request->input('employee_id'),
    //             'date' => $request->input('date'),
    //             'start_time' => $request->input('start_time'),
    //             'end_time' => $request->input('end_time'),
    //             'duty_id' => $request->input('duty_id'),
    //         ]);

    //         $newTask->save();
    //     }

    //     Alert::success('Done', 'Successfully Updated');
    //     return redirect()->route('viewTask');
    // }

    public function deleteTask($id){

        $task = Task::find($id);

        $task->delete();

        Alert::success('Done', 'Successfully Deleted');
        return redirect()->route('viewTask');
    }

    public function viewAdmin() {
        $users = User::where('role', 'admin')->with('position')->get(); // Eager load the positions

        return view('admin.viewAdmin', compact('users'));
    }

    public function createAdmin(){

        return view('admin.createAdmin');
    }

    public function addAdmin(EmployeeRequest $request){

        // Validate the incoming request data
        $validatedData = $request->validated();

        // Create the user record with the validated and modified data
        User::create($validatedData);

        Alert::success('Done', 'Successfully Registered');
        return redirect()->route('viewAdmin');
    }

    public function editAdmin($id) {
        $user = User::find($id);

        return view('admin.editAdmin', compact('user'));
    }

    public function updateAdmin(EmployeeRequest $request, $id) {

        $data = User::find($id);

        // Validate the incoming request data
        $validatedData = $request->validated();

        // Update the user's data based on the validated form input
        $data->update($validatedData);

        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('viewAdmin');
    }

    public function updateAdminPassword(Request $request, $id){
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
        return redirect()->route('viewAdmin');
    }

    public function deleteAdmin($id){

        $employee = User::find($id);

        $employee->delete();
        Alert::success('Done', 'Successfully Deleted');
        return redirect()->route('viewAdmin');
    }

    public function viewSetting(){
        $settings = Setting::all();

        return view('admin.viewSetting', ['settings' => $settings]);
    }

    public function createSetting(){
        return view('admin.createSetting');
    }

    public function addSetting(Request $request){

        // Define validation rules
        $rules = [
            'setting_name' => 'required|max:255',
            'value' => 'required',
            'description' => 'nullable'
        ];

        // Define custom error messages (optional)
        $messages = [
            'setting_name.required' => 'The Setting Name field is required.',
            'setting_name.max' => 'The Setting Name should not exceed 255 characters.',
            'value.required' => 'The Value field is required.',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }


        $setting = new Setting();
        $setting->setting_name = $request->input('setting_name');
        $setting->value = $request->input('value');
        $setting->description = $request->input('description');
        $setting->save();

        Alert::success('Done', 'Successfully Inserted');
        return redirect()->route('viewSetting');
    }

    public function editSetting($id){
        $setting = Setting::find($id);

        return view('admin.editSetting', ['setting' => $setting]);
    }

    public function updateSetting(Request $request, $id){

        // Define validation rules
        $rules = [
            'value' => 'required',
        ];

        // Define custom error messages (optional)
        $messages = [
            'value.required' => 'The Value field is required.',
        ];

            // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

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

        $setting->delete();

        Alert::success('Done', 'Successfully Deleted');
        return redirect()->route('viewSetting');
    }

    public function otApproval() {

        $currentDate = now()->toDateString();
        $otHours = null;

        $punchRecords = PunchRecord::where('ot_approval', '!=', null)->get();

        // Fetch the schedule information for the user.
        $schedule = DB::table('schedules')
                        ->join('users', 'schedules.employee_id', '=', 'users.id')
                        ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')
                        ->join('punch_records', 'punch_records.employee_id', '=', 'schedules.employee_id')
                        ->select('schedules.id', 'schedules.employee_id', 'users.full_name', 'shifts.shift_start', 'shifts.shift_end')
                        ->whereDate('schedules.date', '=', $currentDate)
                        ->whereDate('punch_records.created_at', '=', $currentDate)
                        ->whereNotNull('punch_records.ot_approval') // Add this line to filter based on ot_approval not being null
                        ->first();

        // Check if $schedule is null
        if ($schedule === null) {
            $otStart = 'N/A';
        } else {
            // Fetch the "Overtime Calculation" setting value
            $overtimeCalculation = Setting::where('setting_name', 'Overtime Calculation')->value('value');
            // Get the "Overtime Calculation" setting value
            $overtimeCalculationMinutes = intval($overtimeCalculation);

            // Calculate the "OT Start" based on "Shift End" and overtime calculation
            $otStart = Carbon::parse($schedule->shift_end)->addMinutes($overtimeCalculationMinutes); // Adjust the minutes based on your calculation
        }

        $otapproval = OtApproval::with(['user'])->get();

        return view('admin.otApproval', [
            // 'punchRecords' => $punchRecords,
            // 'otStart' => $otStart,
            // 'otHours' => $otHours,
            'otapproval' => $otapproval,
        ]);
    }

    public function editOtApproval($id){
        $punchRecords = PunchRecord::find($id);
        $users = User::all();

        return view('admin.editOtApproval', [
            'punchRecords' => $punchRecords,
            'users' => $users
        ]);
    }

    // public function updateOtApproval(Request $request, $id) {

    //     $punchRecord = PunchRecord::find($id);
    //     $otapproval = OtApproval::find($id);

    //     // Check if the OtApproval record is found
    //     if ($otapproval) {
    //         // Retrieve the associated punch record where ot_approval is 'Pending'
    //         $punchRecord = PunchRecord::whereDate('created_at', '=', $otapproval->date)
    //             ->where('ot_approval', 'Pending')
    //             ->first();

    //         // Check if the associated punch record is found
    //         if ($punchRecord) {
    //             $clockout = $otapproval->clock_out_time;
    //             $hourAndMinute = Carbon::parse($clockout)->format('H:i');

    //             $clockout = $hourAndMinute;
    //             $shiftEnd = $otapproval->shift_end;

    //             $clockoutTime = Carbon::parse($clockout);
    //             $shiftEndTime = Carbon::parse($shiftEnd);

    //             $minutesDifference = $clockoutTime->diffInMinutes($shiftEndTime);

    //             // You can also get the hours and minutes separately if needed
    //             // $hours = floor($minutesDifference / 60);
    //             $totalHours = $minutesDifference / 60;

    //             $totalHoursRounded = number_format($totalHours, 2);

    //             if ($request->remark == null) {

    //                 $punchRecord->ot_approval = 'Approved';
    //                 $otapproval->status = 'Approved';
    //                 $otapproval->ot_hour = $totalHoursRounded;

    //                 // Retrieve the associated user
    //                 // $user = User::where('id', $punchRecord->employee_id)->first();

    //                 // // Check if the user exists and has schedules
    //                 // if ($user) {
    //                 //     $schedule = Schedule::where('employee_id', $user->id)
    //                 //         ->whereDate('date', $punchRecord->created_at->toDateString())
    //                 //         ->first();

    //                 //     if ($schedule) {
    //                 //         $shift = Shift::find($schedule->shift_id);

    //                 //         if ($shift) {
    //                 //             // Calculate the overtime hours based on the difference between created_at and shift end
    //                 //             $shiftEndTime = Carbon::createFromFormat('H:i', $shift->shift_end);
    //                 //             $createdTime = Carbon::parse($punchRecord->created_at);

    //                 //             // Fetch the "Overtime Calculation" setting value
    //                 //             $overtimeCalculation = Setting::where('setting_name', 'Overtime Calculation')->value('value');
    //                 //             // Get the "Overtime Calculation" setting value
    //                 //             $overtimeCalculationMinutes = intval($overtimeCalculation);

    //                 //             // Calculate the difference in minutes between created_at and shift end
    //                 //             $minutesDifference = $createdTime->diffInMinutes($shiftEndTime);

    //                 //             // Subtract the overtime calculation minutes
    //                 //             $minutesDifference -= $overtimeCalculationMinutes;

    //                 //             // Convert the minutes to hours
    //                 //             $otHours = $minutesDifference / 60;

    //                 //             // Round otHours to 2 decimal places
    //                 //             $otHours = round($otHours, 2);

    //                 //             $punchRecord->ot_hours = $otHours;
    //                 //         }
    //                 //     }
    //                 // }


    //                 $punchRecord->save();
    //                 $otapproval->save();
    //             } else {
    //                 $punchRecord->ot_approval = 'Rejected';
    //                 $punchRecord->remarks = $request->remark;

    //                 $otapproval->status = 'Rejected';
    //                 $otapproval->remark = $request->remark;

    //                 $punchRecord->save();
    //                 $otapproval->save();
    //             }

    //             Alert::success('Done', 'Successfully Updated');
    //             return redirect()->route('otApproval');
    //         } else {
    //             // Handle the case where the associated punch record is not found
    //             Alert::error('Error', 'Associated Punch Record not found.');
    //             return redirect()->route('otApproval');
    //         }


    //     } else {
    //         // Handle the case where the OtApproval record is not found
    //         Alert::error('Error', 'OtApproval Record not found.');
    //         return redirect()->route('otApproval');
    //     }


    // }

    public function updateOtApproval(Request $request, $id) {

        $punchRecord = PunchRecord::find($id);
        $otapproval = OtApproval::find($id);

        // Check if the OtApproval record is found
        if ($otapproval) {
            // Retrieve the associated punch record where ot_approval is 'Pending'
            $punchRecord = PunchRecord::whereDate('created_at', '=', $otapproval->date)
                ->where('ot_approval', 'Pending')
                ->first();

            // Check if the associated punch record is found
            if ($punchRecord) {
                $clockout = $otapproval->clock_out_time;
                $hourAndMinute = Carbon::parse($clockout)->format('H:i');

                $clockout = $hourAndMinute;
                $shiftEnd = $otapproval->shift_end;

                $clockoutTime = Carbon::parse($clockout);
                $shiftEndTime = Carbon::parse($shiftEnd);

                $minutesDifference = $clockoutTime->diffInMinutes($shiftEndTime);

                // You can also get the hours and minutes separately if needed
                // $hours = floor($minutesDifference / 60);
                $totalHours = $minutesDifference / 60;

                $totalHoursRounded = number_format($totalHours, 2);

                if ($request->remark == null) {

                    $punchRecord->ot_approval = 'Approved';
                    $otapproval->status = 'Approved';
                    $otapproval->ot_hour = $totalHoursRounded;
                    $otapproval->approved_ot_hour = $request->approved_ot_hour;

                    $punchRecord->save();
                    $otapproval->save();
                } else {
                    $punchRecord->ot_approval = 'Rejected';
                    $punchRecord->remarks = $request->remark;

                    $otapproval->status = 'Rejected';
                    $otapproval->remark = $request->remark;

                    $punchRecord->save();
                    $otapproval->save();
                }

                Alert::success('Done', 'Successfully Updated');
                return redirect()->route('otApproval');
            } else {
                // Handle the case where the associated punch record is not found
                Alert::error('Error', 'Associated Punch Record not found.');
                return redirect()->route('otApproval');
            }

        } else {
            // Handle the case where the OtApproval record is not found
            Alert::error('Error', 'OtApproval Record not found.');
            return redirect()->route('otApproval');
        }
    }

    public function getOtHour($id){
        // Fetch the PunchRecord by ID
        $punchRecord = OtApproval::find($id);

        if ($punchRecord) {
            // Return the ot_hour as JSON
            return response()->json(['ot_hour' => $punchRecord->ot_hour]);
        } else {
            // Handle the case where PunchRecord is not found
            return response()->json(['error' => 'Record not found'], 404);
        }
    }

    public function deleteOtApproval($id){

        $punchRecords = PunchRecord::find($id);

        $punchRecords->delete();

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

    public function editAttendance($id){
        $punchRecords = PunchRecord::find($id);

        return view('admin.editOtApproval', [
            'punchRecords' => $punchRecords,
        ]);
    }

    public function getAttendanceData($id) {
        $punchRecord = PunchRecord::find($id);

        return response()->json([
            'clock_in_time' => $punchRecord->clock_in_time,
            'clock_out_time' => $punchRecord->clock_out_time,
            'status' => $punchRecord->status,
        ]);
    }

    public function updateAttendance(Request $request, $id){

        $data = PunchRecord::find($id);

        $actualData = PunchRecord::find($id);

        $updateData = [];

        // Check if clock_in_time is provided in the request
        if ($request->has('clock_in_time')) {
            $updateData['clock_in_time'] = $request->input('clock_in_time');
        }

        // Check if clock_out_time is provided in the request
        if ($request->has('clock_out_time')) {
            $updateData['clock_out_time'] = $request->input('clock_out_time');
        }

        // Check if status is provided in the request
        if ($request->has('status')) {
            $updateData['status'] = $request->input('status');
        }

        // Update the user's data based on the form input
        $data->update($updateData);

        // Select only specific fields from $actualData
        $selectedData = $actualData->select('id','clock_in_time', 'clock_out_time', 'employee_id', 'created_at')->first();

        $punch_record_id = $selectedData->id;
        $actual_clock_in_time = $selectedData->clock_in_time;
        $actual_clock_out_time = $selectedData->clock_out_time;
        $date = $selectedData->created_at->format('Y-m-d');
        $employee_id = $selectedData->employee_id;

        $punchRecordLog = new PunchRecordLog();
        $punchRecordLog->punch_record_id = $punch_record_id;
        $punchRecordLog->employee_id = $employee_id;
        $punchRecordLog->record_date = $date;
        $punchRecordLog->actual_clock_in_time = $actual_clock_in_time;
        $punchRecordLog->new_clock_in_time = $request->input('clock_in_time');
        $punchRecordLog->actual_clock_out_time = $actual_clock_out_time;
        $punchRecordLog->new_clock_out_time = $request->input('clock_out_time');
        $punchRecordLog->save();

        Alert::success('Done', 'Successfully Updated');
        return redirect()->route('attendance');
    }

    public function salaryLogs () {
        // Fetch all users with their positions
        $users = User::where('role', 'member')->with('position')->get();

        // Get the OT allowance value from the settings table
        $otAllowanceSetting = Setting::where('setting_name', 'OT Allowance (in RM)')->first();

        if ($otAllowanceSetting) {
            $otAllowanceValue = (float) preg_replace('/[^0-9.]/', '', $otAllowanceSetting->value);
        } else {
            // Default OT allowance value in case the setting is not found
            $otAllowanceValue = 0;
        }

        // Loop through each user to calculate their total OT hours and update/create records for each month
        foreach ($users as $user) {
            $employeeId = $user->employee_id;

            $userId = $user->id;

            // Loop through each month
            for ($month = 1; $month <= 12; $month++) {
                // Query the punch_record table to check if the user has records for the current month
                $hasRecordsForMonth = PunchRecord::where('employee_id', $userId)
                    ->whereMonth('created_at', $month)
                    ->exists();


                // If the user has records for the current month, calculate total_ot_hour
                if ($hasRecordsForMonth) {
                    $otHoursForMonth = OtApproval::selectRaw('SUM(approved_ot_hour) as total_ot_hour')
                        ->where('employee_id', $employeeId)
                        ->whereMonth('created_at', $month)
                        ->value('total_ot_hour');

                    $basicSalary = $user->salary;
                    $totalOTPay = $otHoursForMonth * $otAllowanceValue;
                    $totalPayout = $basicSalary + $totalOTPay;

                    // Format the values with two decimal places
                    $totalOTPayFormatted = number_format($totalOTPay, 2, '.', '');
                    $totalPayoutFormatted = number_format($totalPayout, 2, '.', '');

                    // Find or create a SalaryLog entry for the user and month
                    $salaryLog = SalaryLog::updateOrCreate(
                        [
                            'employee_id' => $userId,
                            'month' => $month,
                            'year' => date('Y'), // You can adjust this as needed
                        ],
                        [
                            'total_ot_hour' => $otHoursForMonth,
                            'total_ot_pay' => $totalOTPayFormatted,
                            'total_payout' => $totalPayoutFormatted,
                        ]
                    );
                }
            }
        }

        // Retrieve the updated records
        $salaryLogs = SalaryLog::whereIn('employee_id', $users->pluck('id')->all())->get();

        return view('admin.salaryLogs', compact('salaryLogs', 'users'));
    }

    // public function totalWork(){
    //     // Fetch punch records with user information
    //     $punchRecords = PunchRecord::with('user')->get();

    //     // Fetch users and their positions
    //     $users = User::where('role', 'member')->with('position')->get();

    //     // Fetch schedules with their shifts
    //     $schedules = Schedule::join('punch_records', 'schedules.date', '=', DB::raw('DATE(punch_records.created_at)'))
    //         ->join('users', 'schedules.employee_id', '=', 'users.id')
    //         ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')
    //         ->select('schedules.id', 'shifts.shift_start', 'shifts.shift_end', 'punch_records.id', 'users.id as employee_id', 'schedules.date', 'punch_records.remarks')
    //         ->get();

    //     // Return the data to the view
    //     return view('admin.totalWork', compact('punchRecords', 'users', 'schedules'));
    // }

    public function totalWork () {
        $punchRecords = PunchRecord::all();
        $users = User::where('role', 'member')->get();
        $schedules = Schedule::join('shifts', 'schedules.shift_id', '=', 'shifts.id')
            ->join('users', 'schedules.employee_id', '=', 'users.id')
            ->select('shifts.shift_start', 'shifts.shift_end', 'schedules.employee_id')
            ->get();

        return view('admin.totalWork', compact('punchRecords', 'users', 'schedules'));
    }

    public function updateTotalWork(Request $request, $id){
        // Validate the form data, including the remark
        $request->validate([
            'remark' => 'string', // Add any additional validation rules as needed
        ]);

        // Find the record you want to update (e.g., PunchRecord)
        $punchRecord = PunchRecord::find($id);

        if (!$punchRecord) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        // Update the record with the new remark
        $punchRecord->remarks = $request->input('remark');
        $punchRecord->save();

        return redirect()->back()->with('success', 'Remark updated successfully.');
    }

    public function recalculateTotalHour(Request $request) {
        // Get the "Late Threshold Minutes" setting value
        $lateThreshold = Setting::where('setting_name', 'Late Threshold (in minutes)')->value('value');

        // Fetch the "Overtime Calculation" setting value
        $overtimeCalculation = Setting::where('setting_name', 'Overtime Calculation (in minutes)')->value('value');

        $selectedRows = $request->input('selectedRows');

        // Accessing specific elements
        foreach ($selectedRows as $row) {
            $date = $row['date'];
            $checkIn = $row['checkIn'];
            $checkOut = $row['checkOut'];
            $shiftId = $row['shiftId'];
            $punchRecordId = $row['punchRecordId'];

            // Parse checkIn and checkOut using Carbon
            $carbonCheckIn = Carbon::createFromFormat('h:i A', $checkIn);
            $carbonCheckOut = Carbon::createFromFormat('h:i A', $checkOut);

            // Retrieve specific columns from the Shift model
            $shiftData = Shift::select('shift_start', 'shift_end')->find($shiftId);

            // Convert shift_start and shift_end to Carbon instances
            $carbonShiftStart = Carbon::parse($shiftData['shift_start']);
            $carbonShiftEnd = Carbon::parse($shiftData['shift_end']);

            // Calculate late by adding lateThreshold to checkIn time
            $carbonCheckLate = $carbonShiftStart->copy()->addMinutes($lateThreshold);

            // Calculate overtime by adding overtimeCalculation to checkOut time
            $carbonCheckOT = $carbonShiftEnd->copy()->addMinutes($overtimeCalculation);

            if($carbonCheckIn->greaterThanOrEqualTo($carbonCheckLate)) {
                // Compare checkOut with checkOT and calculate new total hour accordingly
                if ($carbonCheckOut->greaterThanOrEqualTo($carbonCheckOT)) {
                    // If checkOut is greater than checkOT, calculate new total hour using shift end time
                    $newTotalWork = $carbonShiftEnd->diffInMinutes($carbonCheckIn);
                } else {
                    // If checkOut is not greater than checkOT, calculate new total hour using checkOut time
                    $newTotalWork = $carbonCheckOut->diffInMinutes($carbonCheckIn);
                }
            } else {
                // Compare checkOut with checkOT and calculate new total hour accordingly
                if ($carbonCheckOut->greaterThanOrEqualTo($carbonCheckOT)) {
                    // If checkOut is greater than checkOT, calculate new total hour using shift end time
                    $newTotalWork = ($carbonShiftEnd)->diffInMinutes($carbonShiftStart);
                } else {
                    // If checkOut is not greater than checkOT, calculate new total hour using checkOut time
                    $newTotalWork = ($carbonCheckOut)->diffInMinutes($carbonShiftStart);
                }
            }

            $newTotalWorkInHours = number_format($newTotalWork / 60, 2);

            // Update the total_work column in the PunchRecord model
            PunchRecord::where('id', $punchRecordId)->update(['total_work' => $newTotalWorkInHours]);

            $employee = PunchRecord::join('users', 'punch_records.employee_id', 'users.id')
                                        ->where('punch_records.id', $punchRecordId)
                                        ->select('users.employee_id')
                                        ->first();

            $employee_id = $employee->employee_id;

            $clockOut = PunchRecord::where('id', $punchRecordId)
                                    ->select('clock_out_time')
                                    ->first();

            $clockOutTime = Carbon::parse($clockOut->clock_out_time)->format('H:i:s');

            if ($carbonCheckOut->greaterThanOrEqualTo($carbonCheckOT)) {
                $otMinutesDifference = $carbonCheckOut->diffInMinutes($carbonCheckOT);

                $otInHours = $otMinutesDifference / 60;

                $otInHoursRounded = number_format($otInHours, 2);

                $existingOTRecord = OtApproval::where('date', $date)
                                                ->where('employee_id', $employee_id)
                                                ->where('shift_start', $shiftData['shift_start'])
                                                ->where('shift_end', $shiftData['shift_end']);
                if ($existingOTRecord) {
                    $updateOt = OtApproval::where('date', $date)
                                ->where('employee_id', $employee_id)
                                ->where('shift_start', $shiftData['shift_start'])
                                ->where('shift_end', $shiftData['shift_end'])
                                ->update(['ot_hour'  => $otInHoursRounded]);
                } else {
                    $newOt = OtApproval::create([
                        'employee_id' => $employee_id,
                        'date' => $date,
                        'shift_start' => $shiftData['shift_start'],
                        'shift_end' => $shiftData['shift_end'],
                        'clock_out_time' => $clockOutTime,
                        'ot_hour' => $otInHoursRounded,
                        'status' => 'Pending'
                    ]);
                }

                PunchRecord::where('id', $punchRecordId)->update(['ot_approval' => 'Pending']);
            }
        }

        return response()->json(['message' => 'Successfully updated']);
    }

    public function otherImage($employeeId){
        $user = User::with('otherImages')->find($employeeId);
        $otherImages = $user->otherImages;

        return view('admin.otherImage', compact('user', 'otherImages'));
    }

    public function addOtherImage(Request $request, $employeeId){

        // Validate the request
        $request->validate([
            'new_other_image' => 'required|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:2048',
        ]);

        // Get the employee by ID
        $user = User::find($employeeId);

        $full_name_with_underscores = str_replace(' ', '_', $user->employee_id);

        $file = $request->file('new_other_image');
        $extension = $file->getClientOriginalExtension();
        $otherImageFilename = $full_name_with_underscores . '_other_image.' . time() . '.' . $extension;// Modify the file name
        $file->move('storage/employee/otherImage/', $otherImageFilename);

        // Use Storage::putFileAs to store the file in the desired directory
        // Storage::putFileAs('employee/otherImage', $file, $otherImageFilename);

        // Save the file path to the database
        OtherImage::create([
            'employee_id' => $user->id, // Adjust this based on your authentication logic
            'file_name' => $otherImageFilename,
        ]);

        Alert::success('Done', 'Successfully Inserted');
        return redirect()->back();

    }

    public function deleteOtherImage($employeeId, $imageId){
        // Find the image by ID
        $image = OtherImage::find($imageId);

        // Get the file path
        $filePath = 'storage/employee/otherImage/' . $image->file_name;

        // Delete the file from the file system
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Delete the record from the database
        $image->delete();

        Alert::success('Done', 'Successfully Deleted');
        return redirect()->back();
    }


}
