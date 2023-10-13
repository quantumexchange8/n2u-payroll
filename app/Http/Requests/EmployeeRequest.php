<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required',
            'full_name' => ['required', 'regex:/^[a-zA-Z\s\/\']+$/'],
            'ic_number' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'position_id' => 'nullable',
            'employee_type' => 'nullable',
            'working_hour' => ['required', 'numeric', 'min:1'],
            'employed_since' => 'nullable',
            'nation' => 'required',
            'bank_name' => 'nullable',
            'bank_account' => 'nullable',
            'password' => ['required', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)/'],
            'passport_size_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ic_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'offer_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ];
    }

    public function attributes(): array
    {
        return [
            'employee_id' => 'Employee ID',
            'full_name' => 'Full Name',
            'ic_number' => 'IC Number',
            'address' => 'Address',
            'email' => 'Email',
            'position_id' => 'Position',
            'employee_type' => 'Employee Type',
            'working_hour' => 'Working Hour',
            'employed_since' => 'Employed Since',
            'nation' => 'Nation',
            'bank_name' => 'Bank Name',
            'bank_account' => 'Bank Account',
            'password' => 'Password',
            'passport_size_photo' => 'Passport Size Photo',
            'ic_photo' => 'IC Photo',
            'offer_letter' => 'Offer Letter'
        
        ];
    }
}
