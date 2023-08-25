<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function logout(){

        session()->flush();
        session()->regenerate();

        $notification = array(
            'message'=> "User Account Logout Successfully!",
            'alert-type'=>'info'
        );

        return redirect('/login')->with($notification);
    }

    public function dashboard(){

       return view('admin.dashboard');
    }
}
