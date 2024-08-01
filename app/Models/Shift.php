<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Shift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shift_name'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'shift_id');
    }

    public function shift_schedules()
    {
        return $this->hasMany(ShiftSchedule::class, 'shift_id');
    }
}
