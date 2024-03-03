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

    public $header_status = 0;

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');

        $this->header_status = $this->common->check_header_info();
    }

    public function level_add_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.floor.level.level_add',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.floor.level.level_add_master',compact('menu_data','system_data'));
        }
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

        $duplicate_status_2 = $this->common->get_duplicate_value_two('level_code','building_id','levels', $request->level_code, $request->building_id, 0);

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

            $status = $this->common->add_user_activity_history('levels',$data->id,'Add Level Details');

            if($status==1){

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
                    'message'=> "Something Went Wrong Try Again",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
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

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.floor.level.level_edit',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.floor.level.level_edit_master',compact('menu_data','system_data'));
        }
    }

    public function level_grid(Request $request){

        $menu_data = $this->common->get_page_menu_grid('floor_management.level.add');

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
                ->where(function ($query) use ($search_value) {
                    $query->where('a.level_name','like',"%".$search_value."%")
                        ->orWhere('b.building_name','like',"%".$search_value."%")
                        ->orWhere('a.level_title','like',"%".$search_value."%")
                        ->orWhere('a.level_code','like',"%".$search_value."%");
                })
                ->get();

            $data = DB::table('levels as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->select('a.id', 'a.level_name', 'a.level_title', 'b.building_name', 'a.level_code', 'a.status')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('a.level_name','like',"%".$search_value."%")
                        ->orWhere('b.building_name','like',"%".$search_value."%")
                        ->orWhere('a.level_title','like',"%".$search_value."%")
                        ->orWhere('a.level_code','like',"%".$search_value."%");
                })
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
            $record_data[$sl]['action'] = $this->common->encrypt_data($value->id);
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
        $response['csrf_token'] = csrf_token();

        echo json_encode($response);
    }

    public function level_single_edit_page($encrypt_id){

        $id = $this->common->decrypt_data($encrypt_id);

        $level_data = Level::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('floor_management.level.add****floor_management.level.edit');

        $header_status = $this->header_status;

        if(empty($level_data)){

            if($header_status==1){

                return view('admin.404',compact('menu_data','user_right_data'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.404_master',compact('menu_data','user_right_data','system_data'));
            }
        }
        else{

            if($header_status==1){

                return view('admin.floor.level.level_edit_view',compact('menu_data','level_data','user_right_data','encrypt_id'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.floor.level.level_edit_view_master',compact('menu_data','level_data','user_right_data','encrypt_id','system_data'));
            }
        }
    }

    public function level_update($id, Request $request){

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

        $duplicate_status_1 = $this->common->get_duplicate_value_two('level_name','building_id','levels', $request->level_name, $request->building_id, $request->update_id);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Level Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value_two('level_code','building_id','levels', $request->level_code, $request->building_id, $request->update_id);

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

        $data = Level::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->building_id = $request->building_id;
        $data->level_name = $request->level_name;
        $data->level_code = $request->level_code;
        $data->level_title = $request->level_title;
        $data->level_deatils = $request->level_deatils;
        $data->edit_by = $user_id;
        $data->updated_at = now();
        $data->edit_status = 1;

        $data->save();

        if($data==true){

            $status = $this->common->add_user_activity_history('levels',$data->id,'Edit Level Details');

            if($status==1){

                DB::commit();

                $notification = array(
                    'message'=> "Level Details Updated Successfully",
                    'alert_type'=>'success',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Something Went Wrong Try Again",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Level Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function level_delete_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.floor.level.level_delete',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.floor.level.level_delete_master',compact('menu_data','system_data'));
        }
    }

    public function level_delete($encrypt_id){

        $id = $this->common->decrypt_data($encrypt_id);

        $notification = array();

        $data = Level::where('delete_status',0)
            ->where('id',$id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "Level Data Not Found!!!",
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

                $status = $this->common->add_user_activity_history('levels',$data->id,'Delete Level Details');

                if($status==1){

                    DB::commit();

                    $notification = array(
                        'message'=> "Level Details Deleted Successfully",
                        'alert_type'=>'success',
                        'csrf_token' => csrf_token()
                    );
                }
                else{

                    DB::rollBack();

                    $notification = array(
                        'message'=> "Something Went Wrong Try Again",
                        'alert_type'=>'warning',
                        'csrf_token' => csrf_token()
                    );
                }
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Level Details Not Deleted Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.floor.level.level_delete_alert',compact('menu_data','notification'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.floor.level.level_delete_alert_master',compact('menu_data','notification','system_data'));
        }

    }

    public function level_view_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.floor.level.level_view',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.floor.level.level_view_master',compact('menu_data','system_data'));
        }
    }

    public function level_single_view_page($encrypt_id){

        $id = $this->common->decrypt_data($encrypt_id);

        $level_data = Level::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('floor_management.level.add****floor_management.level.view');

        $header_status = $this->header_status;

        if(empty($level_data)){

            if($header_status==1){

                return view('admin.404',compact('menu_data','user_right_data'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.404_master',compact('menu_data','user_right_data','system_data'));
            }
        }
        else{

            if($header_status==1){

                return view('admin.floor.level.level_single_view',compact('menu_data','level_data','user_right_data','encrypt_id'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.floor.level.level_single_view_master',compact('menu_data','level_data','user_right_data','encrypt_id','system_data'));
            }
        }
    }
}