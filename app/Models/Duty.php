<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Duty extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'duty_name'
    ];

    public function duty(){
        return $this->belongsTo(Duty::class, 'duty_id');
    }

}
