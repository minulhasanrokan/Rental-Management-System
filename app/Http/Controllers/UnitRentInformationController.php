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

    public function unit_rent_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.floor.unit_rent.unit_rent_add',compact('menu_data'));
    }

    public function unit_rent_store (Request $request){

        $validator = Validator::make($request->all(), [
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required',
            'unit_rent' => 'required',
            'water_bill' => 'required',
            'electricity_bill' => 'required',
            'gas_bill' => 'required',
            'security_bill' => 'required',
            'maintenance_bill' => 'required',
            'service_bill' => 'required',
            'charity_bill' => 'required',
            'other_bill' => 'required',
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $errorArray = [];

            foreach ($errors->messages() as $field => $messages) {
                $errorArray[$field] = $messages[0];
            }

            return response()->json([
                'errors' => $errorArray,
                'success' => false,
                'csrf_token' => csrf_token(),
            ]);
        }

        $duplicate_status_1 = $this->common->get_duplicate_value_two('unit_id','building_id,level_id','unit_rent_information', $request->unit_id, $request->building_id.','.$request->level_id, 0);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Unit Rent Information Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new UnitRentInformation;

        $data->building_id = $request->building_id;
        $data->level_id = $request->level_id;
        $data->unit_id = $request->unit_id;
        $data->unit_rent = $request->unit_rent;
        $data->water_bill = $request->water_bill;
        $data->electricity_bill = $request->electricity_bill;
        $data->gas_bill = $request->gas_bill;
        $data->security_bill = $request->security_bill;
        $data->maintenance_bill = $request->maintenance_bill;
        $data->service_bill = $request->service_bill;
        $data->charity_bill = $request->charity_bill;
        $data->other_bill = $request->other_bill;
        $data->add_by = $user_id;
        $data->created_at = now();

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Unit Rent Details Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Unit Rent Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}
