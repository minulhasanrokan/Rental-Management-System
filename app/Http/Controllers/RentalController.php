<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Rent;
use DB;

class RentalController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function unit_rent_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.rent.rent_add',compact('menu_data'));
    }
}
