<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Models\Building;
use DB;

class BuildingController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function building_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.building.building_add',compact('menu_data'));
    }

    public function building_store (Request $request){

        $validator = Validator::make($request->all(), [
            'building_name' => 'required|string|max:250',
            'building_code' => 'required|string|max:20',
            'building_title' => 'required|string|max:250',
            'building_address' => 'required|string|max:250',
            'building_logo'        => 'required',
            'building_photo'        => 'required'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('building_name','buildings', $request->building_name, 0);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Building Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('building_code','buildings', $request->building_code, 0);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Building Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_3 = $this->common->get_duplicate_value('building_title','buildings', $request->building_title, 0);

        if($duplicate_status_3>0){

            $notification = array(
                'message'=> "Duplicate Building Title Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new Building;

        $data->building_name = $request->building_name;
        $data->building_code = $request->building_code;
        $data->building_title = $request->building_title;
        $data->building_address = $request->building_address;
        $data->building_deatils = $request->building_deatils;
        $data->add_by = $user_id;
        $data->created_at = now();

        if ($request->hasFile('building_logo')) {

            $file = $request->file('building_logo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->building_name);

            $fileName = $title_name.'_logo_'.time().'.'.$extension;

            Image::make($file)->resize(500,500)->save('uploads/building/'.$fileName);

            $data->building_logo = $fileName;
        }

        if ($request->hasFile('building_photo')) {

            $file = $request->file('building_photo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->building_name);

            $fileName = $title_name.'_photo_'.time().'.'.$extension;

            Image::make($file)->resize(500,500)->save('uploads/building/'.$fileName);

            $data->building_photo = $fileName;
        }

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Building Details Created Successfully",
                'alert_type'=>'info',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Building Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}
