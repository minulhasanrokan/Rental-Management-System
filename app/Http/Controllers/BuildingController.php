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
                'alert_type'=>'success',
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

    public function building_edit_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.building.building_edit',compact('menu_data'));
    }

    public function building_grid(Request $request){

        $menu_data = $this->common->get_page_menu_grid('floor_management.building.add');

        $csrf_token = csrf_token();

        $draw = $request->draw;
        $row = $request->start;
        $row_per_page  = $request->length;

        $column_index  = $request->order[0]['column'];
        $column_name = $request->columns[$column_index]['data'];

        $column_ort_order = $request->order[0]['dir'];

        $search_value  = trim($request->search['value']);

        $total_data = array();
        $filter_data = array();
        $data = array();
        $record_data = array();
        $response = array();

        if($search_value!=''){

            $total_data = Building::select('id')
                ->where('delete_status',0)
                ->get();

            $filter_data = Building::select('id')
                ->where('delete_status',0)
                ->where('building_name','like',"%".$search_value."%")
                ->orWhere('building_code','like',"%".$search_value."%")
                ->orWhere('building_title','like',"%".$search_value."%")
                ->get();

            $data = Building::select('id','building_logo','building_photo','building_name','building_title','building_code','status')
                ->where('delete_status',0)
                ->where('building_name','like',"%".$search_value."%")
                ->orWhere('building_code','like',"%".$search_value."%")
                ->orWhere('building_title','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = Building::select('id')
                ->where('delete_status',0)
                ->get();

            $data = Building::select('id','building_logo','building_photo','building_name','building_title','building_code','status')
                ->where('delete_status',0)
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();

            $total_data = $filter_data;
        }

        $sl = 0;

        $sl_start = $row+1;

        foreach($data as $value){

            $record_data[$sl]['id'] = $value->id;
            $record_data[$sl]['sl'] = $sl_start;
            $record_data[$sl]['building_logo'] = $value->building_logo;
            $record_data[$sl]['building_photo'] = $value->building_photo;
            $record_data[$sl]['building_name'] = $value->building_name;
            $record_data[$sl]['building_title'] = $value->building_title;
            $record_data[$sl]['building_code'] = $value->building_code;
            $record_data[$sl]['status'] = $value->status;
            $record_data[$sl]['action'] = $value->id;
            $record_data[$sl]['menu_data'] = $menu_data;

            $sl++;
            $sl_start++;
        }

        $total_records = count($total_data);
        $total_records_with_filer = count($filter_data);

        $response['draw'] = $draw;
        $response['iTotalRecords'] = $total_records;
        $response['iTotalDisplayRecords'] = $total_records_with_filer;
        $response['aaData'] = $record_data;
        $response['csrf_token'] = $csrf_token;

        echo json_encode($response);
    }

    public function building_single_edit_page($id){

        $building_data = Building::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('floor_management.building.add****floor_management.building.edit');

        return view('admin.building.building_edit_view',compact('menu_data','building_data','user_right_data'));
    }

    public function building_update($update_id, Request $request){


        $validator = Validator::make($request->all(), [
            'building_name' => 'required|string|max:250',
            'building_code' => 'required|string|max:20',
            'building_title' => 'required|string|max:250',
            'building_address' => 'required|string|max:250',
            'building_logo' => $request->input('hidden_building_logo') === '' ? 'required' : '',
            'building_photo' => $request->input('hidden_building_photo') === '' ? 'required' : ''
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

        $duplicate_status_1 = $this->common->get_duplicate_value('building_name','buildings', $request->building_name, $request->update_id);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Building Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('building_code','buildings', $request->building_code, $request->update_id);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Building Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_3 = $this->common->get_duplicate_value('building_code','buildings', $request->building_title, $request->update_id);

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

        $data = Building::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->building_name = $request->building_name;
        $data->building_code = $request->building_code;
        $data->building_title = $request->building_title;
        $data->building_address = $request->building_address;
        $data->building_deatils = $request->building_deatils;
        $data->edit_by = $user_id;
        $data->edit_status = 1;
        $data->updated_at = now();


        if ($request->hasFile('building_logo')) {

            $file = $request->file('building_logo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->building_name);

            $fileName = $title_name.'_logo_'.time().'.'.$extension;

            Image::make($file)->resize(500,500)->save('uploads/building/'.$fileName);

            if($data->building_logo!='')
            {
                $deletePhoto = "uploads/building/".$data->building_logo;
                
                if(file_exists($deletePhoto)){

                    unlink($deletePhoto);
                }
            }

            $data->building_logo = $fileName;
        }

        if ($request->hasFile('building_photo')) {

            $file = $request->file('building_photo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->building_name);

            $fileName = $title_name.'_photo_'.time().'.'.$extension;

            Image::make($file)->resize(500,500)->save('uploads/building/'.$fileName);

            if($data->building_photo!='')
            {
                $deletePhoto = "uploads/building/".$data->building_photo;
                
                if(file_exists($deletePhoto)){

                    unlink($deletePhoto);
                }
            }

            $data->building_photo = $fileName;
        }

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Building Details Updated Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Building Details Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function building_delete_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.building.building_delete',compact('menu_data'));
    }

    public function building_delete($id){

        $notification = array();

        $data = Building::where('delete_status',0)
            ->where('id',$id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "Building Data Not Found!!!",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }
        else{

            $user_session_data = session()->all();

            $user_id = $user_session_data[config('app.app_session_name')]['id'];

            DB::beginTransaction();

            $data->delete_by = $user_id;
            $data->delete_status = 1;
            $data->deleted_at = now();

            $data->save();

            if($data==true){

                DB::commit();

                $notification = array(
                    'message'=> "Building Details Deleted Successfully",
                    'alert_type'=>'success',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Building Details Not Deleted Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }

        $menu_data = $this->common->get_page_menu();

        return view('admin.building.building_delete_alert',compact('menu_data','notification'));
    }

    public function building_view_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.building.building_view',compact('menu_data'));
    }

    public function building_single_view_page($id){

        $building_data = Building::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('floor_management.building.add****floor_management.building.view');

        return view('admin.building.building_single_view',compact('menu_data','building_data','user_right_data'));
    }
}
