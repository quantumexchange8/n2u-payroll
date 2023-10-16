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
        'position',
        'department_id'
    ];

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // public function department()
    // {
    //     return $this->hasMany(Department::class, 'id', 'department_id');
    // }
}
