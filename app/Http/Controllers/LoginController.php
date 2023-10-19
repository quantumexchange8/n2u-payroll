<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

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
            // Authentication successful, get the authenticated user
            $user = Auth::user();
    
            // Check the user's role and redirect accordingly
            if ($user->role === 'member') {
                Alert::success('Welcome', 'Login Successful.');
                return redirect()->route('homepage'); // Redirect to member dashboard
            } elseif ($user->role === 'admin') {
                Alert::success('Welcome', 'Login Successful.');
                return redirect()->route('admindashboard'); // Redirect to admin dashboard
            }
        } else {
            // Authentication failed, show an error message
            
            return redirect()->back()->with('login_error', 'Invalid employee ID or password.');
                
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
        return redirect()->to('dashboard');
    }

    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect('login');
    }
}
