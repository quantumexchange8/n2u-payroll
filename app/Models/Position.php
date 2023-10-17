<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'position_id',
        'position_name',
        'department_id'
    ];

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function generatePositionId(){
        $latestPosition = static::latest('position_id')->first();

        if (!$latestPosition) {
            return 'P0007';
        }

        $latestId = intval(substr($latestPosition->position_id, 1));
        $newId = str_pad($latestId + 1, 4, '0', STR_PAD_LEFT);

        return 'P' . $newId;
    }


    // public function department()
    // {
    //     return $this->hasMany(Department::class, 'id', 'department_id');
    // }
}
