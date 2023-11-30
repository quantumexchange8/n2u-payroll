<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'employee_id',
        'period_id',
        'duty_id',
        'start_time',
        'end_time'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }

    public function duty(){
        return $this->belongsTo(Duty::class, 'duty_id', 'id');
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class, 'date', 'date');
    }

    public function period(){
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }
}
