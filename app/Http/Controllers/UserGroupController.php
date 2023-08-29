<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserGroupController extends Controller
{

    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }
    
    public function user_group_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.user_group.group_add',compact('menu_data'));

    }
}
