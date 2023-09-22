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

    public function level_edit_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.floor.level.level_edit',compact('menu_data'));
    }

    public function level_grid(Request $request){

        $menu_data = $this->common->get_page_menu_grid('floor_management.level.add');

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

            $total_data = DB::table('levels as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->get();

            $filter_data = DB::table('levels as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('a.level_name','like',"%".$search_value."%")
                ->orWhere('b.building_name','like',"%".$search_value."%")
                ->orWhere('a.level_title','like',"%".$search_value."%")
                ->orWhere('a.level_code','like',"%".$search_value."%")
                ->get();

            $data = DB::table('levels as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->select('a.id', 'a.level_name', 'a.level_title', 'b.building_name', 'a.level_code', 'a.status')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('a.level_name','like',"%".$search_value."%")
                ->orWhere('b.building_name','like',"%".$search_value."%")
                ->orWhere('a.level_title','like',"%".$search_value."%")
                ->orWhere('a.level_code','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = DB::table('levels as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->get();

            $data = DB::table('levels as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->select('a.id', 'a.level_name', 'a.level_title', 'b.building_name', 'a.level_code', 'a.status')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
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
            $record_data[$sl]['level_title'] = $value->level_title;
            $record_data[$sl]['level_name'] = $value->level_name;
            $record_data[$sl]['building_name'] = $value->building_name;
            $record_data[$sl]['level_code'] = $value->level_code;
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
}
