<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PunchRecord;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
class RecordController extends Controller
{
    //

    public function clock_in(Request $request){
        $user = Auth::user();
       
        // Determine whether the user is clocking in or out based on the button text.
        $status = $request->input('status'); // Assuming 'status' corresponds to the button text.
    
        $recordData = [
            'employee_id' => $user->employee_id,
            'in' => null,
            'out' => null,
        ];
    
        if ($status === 'Clock In') {
            $recordData['out'] = 'Clock Out'; // Store "Clock In" in the 'in' column
            Alert::success('Success', 'You have successfully clocked in.');
        } elseif ($status === 'Clock Out') {
            $recordData['in'] = 'Clock In'; // Store "Clock Out" in the 'out' column
            Alert::success('Success', 'You have successfully clocked out.');
        }
    
        $record = PunchRecord::create($recordData);
    
        return redirect()->route('homepage');

    }
    
    
    
}
