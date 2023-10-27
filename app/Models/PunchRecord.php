<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PunchRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'in',
        'out',
        'status',
        'ot_approval',
        'remarks'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id', 'employee_id');
    }
    
    public function schedule() {
        return $this->belongsTo(Schedule::class, 'employee_id', 'employee_id');
    }
    


}
