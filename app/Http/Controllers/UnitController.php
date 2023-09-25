<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Models\Unit;
use DB;

class UnitController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function unit_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.floor.unit.unit_add',compact('menu_data'));
    }

    public function unit_store (Request $request){

        $validator = Validator::make($request->all(), [
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_name' => 'required|string|max:250',
            'unit_code' => 'required|string|max:20',
            'unit_title' => 'required|string|max:250'
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

        $duplicate_status_1 = $this->common->get_duplicate_value_two('unit_name','building_id,level_id','units', $request->level_name, $request->building_id.','.$request->level_id, 0);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Unit Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value_two('unit_code','building_id,level_id','units', $request->level_code, $request->building_id.','.$request->level_id, 0);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Unit Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_3 = $this->common->get_duplicate_value_two('unit_title','building_id,level_id','units', $request->unit_title, $request->building_id.','.$request->level_id, 0);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Unit Title Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new Unit;

        $data->building_id = $request->building_id;
        $data->level_id = $request->level_id;
        $data->unit_name = $request->unit_name;
        $data->unit_code = $request->unit_code;
        $data->unit_title = $request->unit_title;
        $data->unit_deatils = $request->unit_deatils;
        $data->add_by = $user_id;
        $data->created_at = now();

        if ($request->hasFile('unit_photo')) {

            $file = $request->file('unit_photo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->unit_name);

            $fileName = $title_name.'_unit_'.time().'.'.$extension;

            Image::make($file)->resize(500,500)->save('uploads/unit/'.$fileName);

            $data->unit_photo = $fileName;
        }

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Unit Details Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Unit Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}
