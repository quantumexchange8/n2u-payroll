<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PunchRecord extends Model
{
    use HasFactory;

    // status clock
        // const 1 = 'clocked in';
        // const 2 = 'clocked out';
        // const 3 = 'clocked in lunch';
        // const 4 = 'clocked out lunch';

    protected $fillable = [
        'employee_id',
        'in',
        'out',
        'status',
        'ot_approval',
        'ot_hours',
        'remarks',
        'status_clock'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');

        //employee id refer to punch_records.employee_id
        //id refer to users.id
    }
    
    public function schedule() {
        return $this->belongsTo(Schedule::class, 'employee_id', 'employee_id');
    }
    


}
