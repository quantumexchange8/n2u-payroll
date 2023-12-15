<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PunchRecordLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'record_date',
        'actual_clock_in_time',
        'new_clock_in_time',
        'actual_clock_out_time',
        'new_clock_out_time'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }

    public function punchRecord(){
        return $this->belongsTo(PunchRecord::class, 'punch_record_id', 'id');
    }

}
