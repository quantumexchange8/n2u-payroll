<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'schedule_id',
        'date',
        'employee_id',
        'shift_id',
        'duty_id',
        'remarks'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function shift(){
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function duty(){
        return $this->belongsTo(Duty::class, 'duty_id');
    }

    public function generateScheduleId(){
        $latestPosition = static::latest('schedule_id')->first();

        if (!$latestPosition) {
            return 'T0001';
        }

        $latestId = intval(substr($latestPosition->position_id, 1));
        $newId = str_pad($latestId + 1, 4, '0', STR_PAD_LEFT);

        return 'T' . $newId;
    }
}
