<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    
    public function login_page(){

        return view('admin.login_page');
    }

    public function login_store(Request $request){


    }
}
