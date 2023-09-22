<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Level;
use DB;

class LevelController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function level_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.floor.level.level_add',compact('menu_data'));
    }

    public function level_store (Request $request){

        $validator = Validator::make($request->all(), [
            'building_id' => 'required',
            'level_name' => 'required|string|max:250',
            'level_code' => 'required|string|max:20',
            'level_title' => 'required|string|max:250'
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

        $duplicate_status_1 = $this->common->get_duplicate_value_two('level_name','building_id','levels', $request->level_name, $request->building_id, 0);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Level Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value_two('level_name','building_id','levels', $request->level_code, $request->building_id, 0);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Level Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new Level;

        $data->building_id = $request->building_id;
        $data->level_name = $request->level_name;
        $data->level_code = $request->level_code;
        $data->level_title = $request->level_title;
        $data->level_deatils = $request->level_deatils;
        $data->add_by = $user_id;
        $data->created_at = now();

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Level Details Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Level Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}
