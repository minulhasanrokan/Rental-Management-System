<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Models\Notice;
use DB;


class NoticeController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function notice_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.notice.notice_add',compact('menu_data'));
    }

    public function notice_store (Request $request){

        $validator = Validator::make($request->all(), [
            'notice_title' => 'required|string|max:250',
            'notice_date' => 'required|date',
            'notice_status' => 'required',
            'notice_details' => 'required'
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

        $data = new Notice;

        $data->notice_title = $request->notice_title;
        $data->notice_group = $request->notice_group;
        $data->user_id = $request->user_id;
        $data->notice_date = $request->notice_date;
        $data->notice_status = $request->notice_status;
        $data->notice_details = $request->notice_details;
        $data->add_by = $user_id;
        $data->created_at = now();

        if ($request->hasFile('notice_file')) {

            $file = $request->file('notice_file');

            $extension = $file->getClientOriginalExtension();

            $fileName = 'notice_'.time().'.'.$extension;

            $file->move('uploads/notice/', $fileName);

            $data->notice_file = $fileName;
        }

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Notice Details Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Notice Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function notice_view_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.notice.notice_view',compact('menu_data'));
    }

    public function notice_grid (Request $request){

        $menu_data = $this->common->get_page_menu_grid('notice_manage.manage.add');

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

            $total_data = DB::table('notices as a')
                ->leftjoin('user_groups as b', 'b.id', '=', 'a.notice_group')
                ->leftjoin('users as c', 'c.id', '=', 'a.user_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->get();

            $filter_data = DB::table('users as a')
                ->leftjoin('user_groups as b', 'b.id', '=', 'a.notice_group')
                ->leftjoin('users as c', 'c.id', '=', 'a.user_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('a.notice_title','like',"%".$search_value."%")
                ->orWhere('a.notice_date','like',"%".$search_value."%")
                ->orWhere('b.name','like',"%".$search_value."%")
                ->orWhere('c.name','like',"%".$search_value."%")
                ->get();

            $data = DB::table('users as a')
                ->leftjoin('user_groups as b', 'b.id', '=', 'a.notice_group')
                ->leftjoin('users as c', 'c.id', '=', 'a.user_id')
                ->select('a.*','b.group_name','c.name as user_name')
                ->where('a.delete_status',0)
                ->where('a.notice_title','like',"%".$search_value."%")
                ->orWhere('a.notice_date','like',"%".$search_value."%")
                ->orWhere('b.name','like',"%".$search_value."%")
                ->orWhere('c.name','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = DB::table('notices as a')
                ->leftjoin('user_groups as b', 'b.id', '=', 'a.notice_group')
                ->leftjoin('users as c', 'c.id', '=', 'a.user_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->get();

            $data = DB::table('notices as a')
                ->leftjoin('user_groups as b', 'b.id', '=', 'a.notice_group')
                ->leftjoin('users as c', 'c.id', '=', 'a.user_id')
                ->select('a.*','b.group_name','c.name as user_name')
                ->where('a.delete_status',0)
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();

            $total_data = $filter_data;
        }

        $sl = 0;

        $sl_start = $row+1;

        foreach($data as $value){

            $record_data[$sl]['notice_title'] = $value->notice_title;
            $record_data[$sl]['sl'] = $sl_start;
            $record_data[$sl]['notice_date'] = $value->notice_date;
            $record_data[$sl]['notice_file'] = $value->notice_file;
            $record_data[$sl]['notice_status'] = $value->notice_status;
            $record_data[$sl]['group_name'] = $value->group_name;
            $record_data[$sl]['user_name'] = $value->user_name;
            $record_data[$sl]['status'] = $value->status;
            $record_data[$sl]['action'] = $value->id;
            $record_data[$sl]['id'] = $value->id;
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
