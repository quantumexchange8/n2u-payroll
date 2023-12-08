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
        'shift_name',
        'shift_start',
        'shift_end'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'shift_id');
    }

    public function getFormattedShiftTimeAttribute()
    {
        $shift_start = Carbon::parse($this->shift_start);
        $shift_end = Carbon::parse($this->shift_end);

        return $shift_start->format('h:i A') . ' - ' . $shift_end->format('h:i A');
    }
}
