<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    // protected function redirectTo(){
    //     if (Auth()->user()->role_id == 1) {
    //        return route('admin.dashboard');

    //     } elseif(Auth()->user()->role_id == 2) {
    //         return route('head.dashboard');
    //     }elseif(Auth()->user()->role_id == 3) {
    //         return route('sub.dashboard');
    //     }
    //     elseif(Auth()->user()->role_id == 4) {
    //         return route('user.dashboard');
            
    //     }else {
    //        return redirect('/home');
    //     }
    // }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'company_id' => ['required'],
            'emp_id' => ['required', 'string', 'max:100', 'unique:users'],
            'phone' => ['required', 'digits:11'],
            // 'work_place' => ['required'],
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255','regex:/(.*)@(navana-realestate|navanarealestate|navana-construction|navanaengineering|navanawelding|navanafurniture|navanacng|navana-bpl|navana|2s.toyotaservice-bd|navanalpg|navana-logistics|toyotaservice-bd|3s.toyotaservice-bd|navana-hino|navana-iv|navanapower|aftabautomobiles|navana-battery|navana-electronics|navana-parts|navana-foods|navana-biponon|latartebd|navana-motorcycle|navana-headoffice|navanagroup|navanarenergy|navanamarine|toyota)\.com/i', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'emp_id' => $data['emp_id'],
            'phone' => $data['phone'],
            'company_id' => $data['company_id'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            // 'work_place' => $data['work_place']
        ]);
    }
}
