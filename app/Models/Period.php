<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Period extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'period_name'
    ];

    public function tasks(){
        return $this->hasMany(Task::class, 'period_id', 'id');
    }
}
