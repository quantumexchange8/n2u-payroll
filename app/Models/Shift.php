<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shift_id',
        'shift_name',
        'shift_start',
        'shift_end'
    ];

    public function generateShiftId(){
        $latestShift = static::latest('shift_id')->first();

        if (!$latestShift) {
            return 'S0018';
        }

        $latestId = intval(substr($latestShift->shift_id, 1));
        $newId = str_pad($latestId + 1, 4, '0', STR_PAD_LEFT);

        return 'S' . $newId;
    }
}
