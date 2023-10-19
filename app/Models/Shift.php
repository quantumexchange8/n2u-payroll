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
        'shift_id',
        'shift_name',
        'shift_start',
        'shift_end'
    ];

    protected $casts = [
        'shift_start' => 'datetime',
        'shift_end' => 'datetime',
    ];

    public function schedules(){
        return $this->hasMany(Schedule::class);
    }

    public function generateShiftId(){
        $latestShift = static::latest('shift_id')->first();

        if (!$latestShift) {
            return 'S0018';
        }

        $latestId = intval(substr($latestShift->shift_id, 1));
        $newId = str_pad($latestId + 1, 4, '0', STR_PAD_LEFT);

        return 'S' . $newId;
    }

    public function getFormattedShiftTimeAttribute()
{
    $shift_start = Carbon::parse($this->shift_start);
    $shift_end = Carbon::parse($this->shift_end);

    return $shift_start->format('h:i A') . ' - ' . $shift_end->format('h:i A');
}
}
