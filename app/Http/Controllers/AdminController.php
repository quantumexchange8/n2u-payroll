<?php

namespace App\Http\Controllers;

use App\Models\PunchRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //

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

    public function employee(){
        return view('admin.employee');
    }

    public function addEmployee(Request $request){
        //dd($request->all());

        $data = $request->validate([
            'employee_id' => 'required',
            'full_name'=>'required',
            'address'=>'required',
            'email'=>'required',
            'position_id'=>'nullable',
            'employee_type'=>'nullable',
            'working_hour'=>'required|numeric',
            'bank_name'=>'nullable',
            'bank_account'=>'nullable',
            'passport_size_photo'=>'nullable',
            'ic_photo'=>'nullable',
            'offer_letter'=>'nullable',
            'password' => 'required',
        ]);

        $data['password'] = Hash::make($request->password);

        if($data){
            User::create($data);
        } else {
            return redirect()->back();
        }

        return redirect()->route('admindashboard');
    }
}
