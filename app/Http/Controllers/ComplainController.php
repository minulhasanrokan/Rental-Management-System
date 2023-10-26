<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Complain;
use DB;

class ComplainController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function complain_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.complain.complain_add',compact('menu_data'));
    }

    public function complain_store(Request $request){

        $validator = Validator::make($request->all(), [
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required',
            'tenant_name' => 'required',
            'tenant_id' => 'required',
            'complain_title' => 'required|string|max:250',
            'complain_details' => 'required'
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

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new Complain;

        $data->building_id = $request->building_id;
        $data->level_id = $request->level_id;
        $data->unit_id = $request->unit_id;
        $data->tenant_name = $request->tenant_name;
        $data->tenant_id = $request->tenant_id;
        $data->complain_title = $request->complain_title;
        $data->complain_details = $request->complain_details;
        $data->add_by = $user_id;
        $data->created_at = now();

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Complain Details Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Complain Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
} 
