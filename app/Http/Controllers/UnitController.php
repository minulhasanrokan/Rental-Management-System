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
            'unit_title' => 'required|string|max:250',
            'unit_photo'  => 'required',
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

    public function unit_edit_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.floor.unit.unit_edit',compact('menu_data'));
    }

    public function unit_grid(Request $request){
        
        DB::enableQueryLog();

        $menu_data = $this->common->get_page_menu_grid('floor_management.unit.add');

        $user_config_data = $this->common->get_user_config_data();

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

            $total_data = DB::table('units as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                //->where('b.id','c.building_id')
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->get();

            $filter_data = DB::table('units as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                //->where('b.id','c.building_id')
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('c.level_name','like',"%".$search_value."%")
                ->orWhere('b.building_name','like',"%".$search_value."%")
                ->orWhere('a.unit_name','like',"%".$search_value."%")
                ->orWhere('a.unit_code','like',"%".$search_value."%")
                ->orWhere('a.unit_title','like',"%".$search_value."%")
                ->get();

            $data = DB::table('units as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->select('a.id', 'a.unit_name', 'a.unit_title', 'a.unit_code', 'b.building_name', 'b.building_logo', 'a.unit_photo', 'c.level_name', 'a.status')
                ->where('a.delete_status',0)
                //->where('b.id','c.building_id')
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('c.level_name','like',"%".$search_value."%")
                ->orWhere('b.building_name','like',"%".$search_value."%")
                ->orWhere('a.unit_name','like',"%".$search_value."%")
                ->orWhere('a.unit_code','like',"%".$search_value."%")
                ->orWhere('a.unit_title','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = DB::table('units as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                //->where('b.id','c.building_id')
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->get();

            $data = DB::table('units as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->select('a.id', 'a.unit_name', 'a.unit_title', 'a.unit_code', 'b.building_name', 'b.building_logo', 'a.unit_photo', 'c.level_name', 'a.status')
                ->where('a.delete_status',0)
                //->where('b.id','c.building_id')
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();

            $total_data = $filter_data;
        }

        //$query = DB::getQueryLog();
        //dd($query);

        $sl = 0;

        $sl_start = $row+1;

        foreach($data as $value){

            $record_data[$sl]['id'] = $value->id;
            $record_data[$sl]['sl'] = $sl_start;
            $record_data[$sl]['unit_name'] = $value->unit_name;
            $record_data[$sl]['unit_title'] = $value->unit_title;
            $record_data[$sl]['unit_code'] = $value->unit_code;
            $record_data[$sl]['building_name'] = $value->building_name;
            $record_data[$sl]['building_logo'] = $value->building_logo;
            $record_data[$sl]['unit_photo'] = $value->unit_photo;
            $record_data[$sl]['level_name'] = $value->level_name;
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

    public function unit_single_edit_page($id){

        $unit_data = Unit::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('floor_management.unit.add****floor_management.unit.edit');

        return view('admin.floor.unit.unit_edit_view',compact('menu_data','unit_data','user_right_data'));
    }

    public function unit_update($id, Request $request){


        $validator = Validator::make($request->all(), [
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_name' => 'required|string|max:250',
            'unit_code' => 'required|string|max:20',
            'unit_title' => 'required|string|max:250',
            'unit_photo' => $request->input('hidden_unit_photo') === '' ? 'required' : '',
        ]);

        $duplicate_status_1 = $this->common->get_duplicate_value_two('unit_name','building_id,level_id','units', $request->level_name, $request->building_id.','.$request->level_id, $request->update_id);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Unit Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value_two('unit_code','building_id,level_id','units', $request->level_code, $request->building_id.','.$request->level_id, $request->update_id);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Unit Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_3 = $this->common->get_duplicate_value_two('unit_title','building_id,level_id','units', $request->unit_title, $request->building_id.','.$request->level_id, $request->update_id);

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

        $data = Unit::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        if ($request->hasFile('unit_photo')) {

            $file = $request->file('unit_photo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->unit_name);

            $fileName = $title_name.'_unit_'.time().'.'.$extension;

            Image::make($file)->resize(500,500)->save('uploads/unit/'.$fileName);

            if($data->unit_photo!='')
            {
                $deletePhoto = "uploads/unit/".$data->unit_photo;
                
                if(file_exists($deletePhoto)){

                    unlink($deletePhoto);
                }
            }

            $data->unit_photo = $fileName;
        }

        $data->building_id = $request->building_id;
        $data->level_id = $request->level_id;
        $data->unit_name = $request->unit_name;
        $data->unit_code = $request->unit_code;
        $data->unit_title = $request->unit_title;
        $data->unit_deatils = $request->unit_deatils;
        $data->edit_by = $user_id;
        $data->updated_at = now();
        $data->edit_status = 1;

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Unit Details Updated Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Unit Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}
