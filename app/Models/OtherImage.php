<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['employee_id', 'file_name'];

    public function users(){
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }
}
