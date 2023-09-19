<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected function redirectTo(){
        if (Auth()->user()->role_id == 1) {
           return route('admin.dashboard');

        } elseif(Auth()->user()->role_id == 2) {
            
            return route('head.dashboard');

        }elseif(Auth()->user()->role_id == 3) {
            return route('sub.dashboard');
            
        }elseif(Auth()->user()->role_id === 4) {
            return route('user.dashboard');
        }else {
           return redirect('/home');
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        //$this->emp_id = $this->findUsername();
    }


    // public function findUsername()
    // {
    //     $emp_id = request()->input('emp_id');
 
    //     $fieldType = filter_var($emp_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'emp_id';
 
    //     request()->merge([$fieldType => $emp_id]);
 
    //     return $fieldType;
    // }
 
    // public function emp_id()
    // {
    //     return $this->emp_id;
    // }

    
    //second method
    public function login(Request $request)
    {
        $this->validate($request, [
            'emp_id'    => 'required',
            'password' => 'required',
        ]);

        $login_type = filter_var($request->input('emp_id'), FILTER_VALIDATE_EMAIL ) 
            ? 'email' 
            : 'emp_id';

        $request->merge([
            $login_type => $request->input('emp_id')
        ]);

        if (Auth::attempt($request->only($login_type, 'password'))) {
            return redirect()->intended($this->redirectPath());
        }

        return redirect()->back()
            ->withInput()
            ->withErrors([
                'emp_id' => 'These credentials do not match our records.',
            ]);
    } 

    



}
