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

    public function assign_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.complain.complain_assign',compact('menu_data'));
    }

    public function status_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.complain.complain_status',compact('menu_data'));
    }

    public function assign_single_page($id){

        $complain_data = Complain::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('complain_management.manage.add****complain_management.manage.assign');

        return view('admin.complain.complain_assign_edit_view',compact('menu_data','complain_data','user_right_data'));
    }

    public function status_single_page($id){

        $complain_data = Complain::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('complain_management.manage.add****complain_management.manage.status');

        return view('admin.complain.complain_status_edit_view',compact('menu_data','complain_data','user_right_data'));
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

    public function complain_view_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.complain.complain_view',compact('menu_data'));
    }

    public function complain_grid(Request $request){

        $menu_data = $this->common->get_page_menu_grid('complain_management.manage.add');

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

        DB::enableQueryLog();

        if($search_value!=''){

            $total_data = DB::table('complains as a')
                ->leftJoin('users as b', 'b.id', '=', 'a.assign_user')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->get();

            $filter_data = DB::table('complains as a')
                ->leftJoin('users as b', 'b.id', '=', 'a.assign_user')
                ->where('a.delete_status',0)
                ->where('a.complain_title','like',"%".$search_value."%")
                ->get();

            $data = DB::table('complains as a')
                ->leftJoin('users as b', 'b.id', '=', 'a.assign_user')
                ->select('a.id','a.complain_title','a.created_at','a.assign_user','a.process_status','a.status','b.name as user_name')
                ->where('a.delete_status',0)
                ->where('a.complain_title','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = DB::table('complains as a')
                ->leftJoin('users as b', 'b.id', '=', 'a.assign_user')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->get();

            $data = DB::table('complains as a')
                ->leftJoin('users as b', 'b.id', '=', 'a.assign_user')
                ->select('a.id','a.complain_title','a.created_at','a.assign_user','a.process_status','a.status','b.name as user_name')
                ->where('a.delete_status',0)
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
            $record_data[$sl]['complain_title'] = $value->complain_title;
            $record_data[$sl]['created_at'] = date('d-m-Y h:i:sa',strtotime($value->created_at));
            $record_data[$sl]['assign_user'] = $value->assign_user;
            $record_data[$sl]['process_status'] = $value->process_status;
            $record_data[$sl]['user_name'] = $value->user_name;
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

    public function complain_status_grid(Request $request){

        $menu_data = $this->common->get_page_menu_grid('complain_management.manage.add');

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

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

        DB::enableQueryLog();

        if($search_value!=''){

            $total_data = DB::table('complains as a')
                ->join('users as b', 'b.id', '=', 'a.assign_user')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('a.assign_user',$user_id)
                ->get();

            $filter_data = DB::table('complains as a')
                ->join('users as b', 'b.id', '=', 'a.assign_user')
                ->where('a.delete_status',0)
                ->where('a.assign_user',$user_id)
                ->where('a.complain_title','like',"%".$search_value."%")
                ->get();

            $data = DB::table('complains as a')
                ->join('users as b', 'b.id', '=', 'a.assign_user')
                ->select('a.id','a.complain_title','a.created_at','a.assign_user','a.process_status','a.status','b.name as user_name')
                ->where('a.delete_status',0)
                ->where('a.assign_user',$user_id)
                ->where('a.complain_title','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = DB::table('complains as a')
                ->join('users as b', 'b.id', '=', 'a.assign_user')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('a.assign_user',$user_id)
                ->get();

            $data = DB::table('complains as a')
                ->join('users as b', 'b.id', '=', 'a.assign_user')
                ->select('a.id','a.complain_title','a.created_at','a.assign_user','a.process_status','a.status','b.name as user_name')
                ->where('a.delete_status',0)
                ->where('a.assign_user',$user_id)
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
            $record_data[$sl]['complain_title'] = $value->complain_title;
            $record_data[$sl]['created_at'] = date('d-m-Y h:i:sa',strtotime($value->created_at));
            $record_data[$sl]['assign_user'] = $value->assign_user;
            $record_data[$sl]['process_status'] = $value->process_status;
            $record_data[$sl]['user_name'] = $value->user_name;
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

    public function assign_add($id, Request $request){

        $validator = Validator::make($request->all(), [
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required',
            'tenant_name' => 'required',
            'tenant_id' => 'required',
            'complain_title' => 'required|string|max:250',
            'assign_user' => 'required',
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

        $data = Complain::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->assign_user = $request->assign_user;
        $data->assign_by = $request->user_id;
        $data->assign_date = now();

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Complain Assign User Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Complain Assign User Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function status_add ($id, Request $request){

        $validator = Validator::make($request->all(), [
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required',
            'tenant_name' => 'required',
            'tenant_id' => 'required',
            'complain_title' => 'required|string|max:250',
            'assign_user' => 'required',
            'process_status' => 'required',
            'complain_details' => 'required',
            'complain_remarks' => 'required',
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

        $data = Complain::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->process_status = $request->process_status;
        $data->complain_remarks = $request->complain_remarks;
        $data->complate_date = now();

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Complain Status Updated Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Complain Status Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
} 
