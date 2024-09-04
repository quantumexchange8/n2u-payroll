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
use App\Models\ShiftSchedule;
use App\Models\Task;
use App\Models\User;
use App\Models\Period;
use App\Models\OtApproval;
use App\Models\OtherImage;
use App\Models\Outlet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AjaxController extends Controller
{
    //
    public function changeNameByOutlet (Request $request){

        $outlet_id = $request->input('outlet_id');
        // $users = User::all();
        $employees = User::where('outlet_id', $outlet_id)->get(['nickname as nickname', 'id as id']);
        // $employee_name = $users->where('outlet_id', $outlet_id)->chunk(ceil($users->count() / 4));
        return response() -> json($employees);
    }

    public function displayShift (Request $request){
        $shift_id = $request->input('shift_id');
        // $shift_details = ShiftSchedule::leftJoin('shifts', 'shift_schedules.shift_id' , '=' , 'shifts.id')->where('shift_id', $shift_id)->get(['id as id', 'shift_name as shift_name', 'shift_id as shift_id', 'shift_start as shift_start', 'shift_end as shift_end', 'shift_days as shift_days']);
        $shift_details = Shift::with('shift_schedules')->where('id', $shift_id)->get();
        // dd($shift_details);
        return response()->json($shift_details);
    }

    public function displayShiftTime (Request $request){
        $shift_id = $request->input('shift_id');
        $shift_time = ShiftSchedule::where('shift_id', $shift_id)->get();

        return response()->json($shift_time);
    }

    public function filterScheduleByEmployee (Request $request){ //in-progress
        $search = $request->query('employee_id', '');

        $query = Schedule::query();

        if ($search) {
            $query->where('employee_id', 'like', '%$search%');
        }

        $schedules = $query
                    ->orderBy('date', 'asc')
                    ->paginate(15);

        // return response()->json([
        //     'html' => view('admin.scheduleReportContent', compact('schedules'))->render()
        // ]);
        return redirect()->view('schedule-report');
    }

    public function filterScheduleByDate (Request $request){
        $schedule = Schedule::where('date', $request->input())
                    ->get();

        return response()->json($schedule);
    }

}
