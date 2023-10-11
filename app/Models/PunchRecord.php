<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PunchRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id'
    ];


    public function users()
    {
        return $this->belongsTo(User::class, 'employee_id', 'employee_id');
    }


}
