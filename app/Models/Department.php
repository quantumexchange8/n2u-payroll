<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_id',
        'department_name'
    ];

    public function positions(){
        return $this->hasMany(Position::class, 'department_id', 'department_id');
    }

    public function generateDepartmentId(){
        $latestDepartment = static::latest('department_id')->first();

        if (!$latestDepartment) {
            return 'D0003';
        }

        $latestId = intval(substr($latestDepartment->department_id, 1));
        $newId = str_pad($latestId + 1, 4, '0', STR_PAD_LEFT);

        return 'D' . $newId;
    }

    public function setDepartmentNameAttribute($value){
        $this->attributes['department_name'] = ucwords($value);
    }



}
