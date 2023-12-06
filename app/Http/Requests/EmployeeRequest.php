<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class EmployeeRequest extends FormRequest
{



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */



    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('id'); // Assuming your route parameter is named 'user'

        $rules = [
            'employee_id' => 'required|unique:users,employee_id,' . $userId,
            'full_name' => 'required',
            'nickname' => 'required',
            'ic_number' => 'required|unique:users,ic_number,' . $userId,
            'address' => 'nullable',
            'email' => 'nullable',
            'position_id' => 'nullable',
            'employee_type' => 'nullable',
            'remarks' => 'nullable',
            'salary' => ['nullable', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'employed_since' => 'nullable',
            'nation' => 'required',
            'bank_name' => 'nullable',
            'bank_account' => 'nullable',
            'account_type' => 'nullable',
            'account_id' => 'nullable',
            'account_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'other_image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'required',
            'passport_size_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ic_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'offer_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'role' => 'required',
        ];

        // If creating a new user, add the unique and required rules for 'employee_id'
        // if (!$userId) {
        //     // Initialize $rules['employee_id'] as an array
        //     $rules['employee_id'] = [];
        //     $rules['ic_number'] = [];

        //     $rules['employee_id'][] = Rule::unique('users', 'employee_id');
        //     $rules['ic_number'][] = Rule::unique('users', 'ic_number');
        // }

        return $rules;
    }

    public function attributes()
    {
        return [
            'employee_id' => 'Employee ID',
            'full_name' => 'Full Name',
            'nickname' => 'Nickname',
            'ic_number' => 'IC Number',
            'address' => 'Address',
            'email' => 'Email',
            'position_id' => 'Position',
            'employee_type' => 'Employee Type',
            'remarks' => 'Remarks',
            'salary' => 'Salary',
            'employed_since' => 'Employed Since',
            'nation' => 'Nationality',
            'bank_name' => 'Bank Name',
            'bank_account' => 'Bank Account',
            'password' => 'Password',
            'passport_size_photo' => 'Passport Size Photo',
            'ic_photo' => 'IC Photo',
            'offer_letter' => 'Offer Letter',
            'role' => 'Role',

        ];
    }

    // public function messages()
    // {
    //     return [
    //         'employee_id.unique' => 'The provided Employee ID is already in use.',
    //         'ic_number.unique' => 'The provided IC Number / Passport is already in use.',
    //     ];
    // }
}
