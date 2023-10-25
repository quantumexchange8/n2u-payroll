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

}
