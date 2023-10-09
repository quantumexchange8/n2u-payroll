<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PunchRecord;
use Auth;

class RecordController extends Controller
{
    //

    public function clock_in(Request $request)
    {

        $user = Auth::user();

        // dd($user->employee_id);

        $record = PunchRecord::create([
            'employee_id' => $user->employee_id,
        ]);

        return redirect()->back();
    }
}
