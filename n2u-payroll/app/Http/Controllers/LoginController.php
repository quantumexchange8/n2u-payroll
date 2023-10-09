<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    //

    public function login()
    {
        return view('auth.login');
    }

    public function login_post(Request $request)
    {

        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'e_id' => 'required',
            'password' => 'required',
        ], [
            'e_id.required' => 'Employee ID is required.',
            'password.required' => 'Password is required.',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        // Attempt to authenticate the user
        $credentials = [
            'employee_id' => $request->input('e_id'),
            'password' => $request->input('password'),
        ];
    
        if (Auth::attempt($credentials)) {
            // Authentication successful, redirect to the desired page
            return redirect('dashboard');
        } else {
            // Authentication failed, show an error message
            return redirect()->back()
                ->with('login_error', 'Invalid employee ID or password.');
        }
    }

    public function register()
    {
        return view('auth.register');
    }

    public function register_post(Request $request)
    {

        // dd($request->all());
        
        $user = User::create([
            'name' => $request->f_name,
            'employee_id' => $request->e_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('login');
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect()->to('index');
    }

    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect('login');
    }
}
