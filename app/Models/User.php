<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'full_name',
        'ic_number',
        'address',
        'email',
        'position_id',
        'employee_type',
        'salary',
        'employed_since',
        'nation',
        'bank_name',
        'bank_account',
        'passport_size_photo',
        'ic_photo',
        'offer_letter',
        'password',
        'role'
    ];
    
    public function position() {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function schedules(){
        return $this->hasMany(Schedule::class, 'employee_id', 'id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    
}
