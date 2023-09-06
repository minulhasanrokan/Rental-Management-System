<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }
    
    public function user_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.user.user_add',compact('menu_data'));
    }
}
