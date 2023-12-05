<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UserType;
use DB;

class UserTypeController extends Controller
{
    public $common;

    public $app_session_name ='';

    public $header_status = 0;

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');

        $this->header_status = $this->common->check_header_info();
    }

    public function user_type_add_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.user_type.user_type_add',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.user_type.user_type_add_master',compact('menu_data','system_data'));
        }
    }

    public function user_type_store (Request $request){

        $validator = Validator::make($request->all(), [
            'user_type_name' => 'required|string|max:250',
            'user_type_code' => 'required|string|max:20',
            'user_type_title' => 'required|string|max:250'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('user_type_name','user_types', $request->user_type_name, 0);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate User Type Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('user_type_code','user_types', $request->user_type_code, 0);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate User Type Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new UserType;

        $data->user_type_name = $request->user_type_name;
        $data->user_type_code = $request->user_type_code;
        $data->user_type_title = $request->user_type_title;
        $data->user_type_deatils = $request->user_type_deatils;
        $data->add_by = $user_id;
        $data->created_at = now();

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "User Type Details Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "User Type Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function user_type_edit_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.user_type.user_type_edit',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.user_type.user_type_edit_master',compact('menu_data','system_data'));
        }
    }

    public function user_type_grid(Request $request){

        $menu_data = $this->common->get_page_menu_grid('reference_data.user.type.add');

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

            $total_data = UserType::select('id')
                ->where('delete_status',0)
                ->get();

            $filter_data = UserType::select('id')
                ->where('delete_status',0)
                ->where('user_type_name','like',"%".$search_value."%")
                ->orWhere('user_type_code','like',"%".$search_value."%")
                ->orWhere('user_type_title','like',"%".$search_value."%")
                ->get();

            $data = UserType::select('id','user_type_name','user_type_code','user_type_title','user_type_deatils','add_by','edit_by','status')
                ->where('delete_status',0)
                ->where('user_type_name','like',"%".$search_value."%")
                ->orWhere('user_type_code','like',"%".$search_value."%")
                ->orWhere('user_type_title','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = UserType::select('id')
                ->where('delete_status',0)
                ->get();

            $data = UserType::select('id','user_type_name','user_type_code','user_type_title','user_type_deatils','add_by','edit_by','status')
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
            $record_data[$sl]['user_type_name'] = $value->user_type_name;
            $record_data[$sl]['user_type_code'] = $value->user_type_code;
            $record_data[$sl]['user_type_title'] = $value->user_type_title;
            $record_data[$sl]['user_type_deatils'] = $value->user_type_deatils;
            $record_data[$sl]['add_by'] = $value->add_by;
            $record_data[$sl]['edit_by'] = $value->edit_by;
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

    public function user_type_single_edit_page($id){

        $user_type_data = UserType::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('reference_data.user.type.add****reference_data.user.type.edit');

        $header_status = $this->header_status;

        if(empty($user_type_data)){

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

                return view('admin.reference.user_type.user_type_edit_view',compact('menu_data','user_type_data','user_right_data'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.reference.user_type.user_type_edit_view_master',compact('menu_data','user_type_data','user_right_data','system_data'));
            }
        }
    }

    public function user_type_update($id, Request $request){

        $validator = Validator::make($request->all(), [
            'user_type_name' => 'required|string|max:250',
            'user_type_code' => 'required|string|max:20',
            'user_type_title' => 'required|string|max:250'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('user_type_name','user_types', $request->user_type_name, $request->update_id);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate User Type Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('user_type_code','user_types', $request->user_type_code, $request->update_id);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate User Type Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = UserType::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->user_type_name = $request->user_type_name;
        $data->user_type_code = $request->user_type_code;
        $data->user_type_title = $request->user_type_title;
        $data->user_type_deatils = $request->user_type_deatils;
        $data->edit_by = $user_id;
        $data->updated_at = now();
        $data->edit_status = 1;

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "User Type Details Updated Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "User Type Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function user_type_view_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.user_type.user_type_view',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.user_type.user_type_view_master',compact('menu_data','system_data'));
        }
    }

    public function user_type_single_view_page($id){

        $user_type_data = UserType::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('reference_data.user.type.add****reference_data.user.type.view');

        $header_status = $this->header_status;

        if(empty($user_type_data)){

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

                return view('admin.reference.user_type.user_type_single_view',compact('menu_data','user_type_data','user_right_data'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.reference.user_type.user_type_single_view_master',compact('menu_data','user_type_data','user_right_data','system_data'));
            }
        }
    }

    public function user_type_delete_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.user_type.user_type_delete',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.user_type.user_type_delete_master',compact('menu_data','system_data'));
        }
    }

    public function user_type_delete($id){

        $notification = array();

        $data = UserType::where('delete_status',0)
            ->where('id',$id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "User Type Data Not Found!!!",
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
                    'message'=> "User Type Details Deleted Successfully",
                    'alert_type'=>'success',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "User Type Details Not Deleted Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.user_type.user_type_delete_alert',compact('menu_data','notification'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.user_type.user_type_delete_alert_master',compact('menu_data','notification','system_data'));
        }
    }
}
