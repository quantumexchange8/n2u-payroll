<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'basic_salary',
        'total_ot_hour',    //total_ot_hour = total ot hour in month
        'total_ot_pay',     //total_ot_pay = total_ot_hour * ot_allowance, ot_allowance set in table settings
        'total_payout',     //total_payout = basic_salary + total_ot_pay
        'month',
        'year'        
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');

        //employee id refer to salary_logs.employee_id
        //id refer to users.id
    }
}
