<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UnitRentInformation;
use DB;

class UnitRentInformationController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function unit_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.floor.unit_rent.unit_rent_add',compact('menu_data'));
    }
}
