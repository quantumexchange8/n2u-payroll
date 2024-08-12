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
        $employees = User::where('outlet_id', $outlet_id)->get(['nickname as text', 'id as value']);
        // $employee_name = $users->where('outlet_id', $outlet_id)->chunk(ceil($users->count() / 4));
        // dd($employee_name);
        // return response()->json( $employee_name[$outlet_id] ?? []);
        return response() -> json($employees);
        // return view('admin.createSchedule', compact('employee_name'));
    }

}
