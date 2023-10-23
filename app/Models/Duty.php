<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Duty extends Model
{
    use HasFactory;

    protected $fillable = [
        "duty_id",
        "duty_name"
    ];

    public function generateDutyId(){
        $latestDuty = static::latest('duty_id')->first();

        if (!$latestDuty) {
            return 'J0001';
        }

        $latestId = intval(substr($latestDuty->duty_id, 1));
        $newId = str_pad($latestId + 1, 4, '0', STR_PAD_LEFT);

        return 'J' . $newId;
    }
}
