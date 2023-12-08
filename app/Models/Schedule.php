<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'employee_id',
        'shift_id',
        'duty_id',
        'remarks',
        'off_day'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }

    public function shift(){
        return $this->belongsTo(Shift::class, 'shift_id');
    }
    public function tasks(){
        return $this->hasMany(Task::class, 'date', 'date');
    }
}
