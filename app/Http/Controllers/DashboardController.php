<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public $common;

    public function __construct(){

        $this->common = new CommonController();
    }

    public function dashboard(){

       return view('admin.dashboard');
    }
}
