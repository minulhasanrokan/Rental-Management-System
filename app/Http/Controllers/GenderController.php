<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Gender;
use DB;

class GenderController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function gender_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.gender.gender_add',compact('menu_data'));
    }

    public function gender_add_store(Request $request){

        $validator = Validator::make($request->all(), [
            'gender_name' => 'required|string|max:250',
            'gender_code' => 'required|string|max:20',
            'gender_title' => 'required|string|max:250'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('gender_name','genders', $request->gender_name, 0);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Gender Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('gender_code','genders', $request->gender_code, 0);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Gender Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new Gender;

        $data->gender_name = $request->gender_name;
        $data->gender_code = $request->gender_code;
        $data->gender_title = $request->gender_title;
        $data->gender_deatils = $request->gender_deatils;
        $data->add_by = $user_id;
        $data->created_at = now();

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Gender Details Created Successfully",
                'alert_type'=>'info',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Gender Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}
