<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CustomLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customLoginForm($id)
    {
        $user = User::find($id);
        return view('auth.custom-login', ['user'=>$user]);
    }
}
