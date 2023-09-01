<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Models\UserGroup;
use DB;

class UserGroupController extends Controller
{

    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }
    
    public function user_group_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.user_group.group_add',compact('menu_data'));
    }

    public function user_group_store(Request $request){

        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|max:250',
            'group_code' => 'required|string|max:20',
            'group_title' => 'required|string|max:250',
            'group_logo' => 'required',
            'group_icon' => 'required'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('group_name','user_groups', $request->group_name, 0);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Group Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('group_code','user_groups', $request->group_code, 0);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Group Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new UserGroup;

        $data->group_name = $request->group_name;
        $data->group_code = $request->group_code;
        $data->group_title = $request->group_title;
        $data->group_deatils = $request->group_deatils;
        $data->add_by = $user_id;

        if ($request->hasFile('group_logo')) {

            $file = $request->file('group_logo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->group_name);

            $fileName = $title_name.'_logo_'.time().'.'.$extension;

            Image::make($file)->resize(500,500)->save('uploads/user_group/'.$fileName);

            $data->group_logo = $fileName;
        }

        if ($request->hasFile('group_icon')) {

            $file = $request->file('group_icon');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->group_name);

            $fileName = $title_name.'_icon_'.time().'.'.$extension;

            Image::make($file)->resize(100,100)->save('uploads/user_group/'.$fileName);

            $data->group_icon = $fileName;
        }

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "User Group Details Created Successfully",
                'alert_type'=>'info',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "User Group Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function user_group_view_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.user_group.group_view',compact('menu_data'));
    }

    public function user_group_grid(Request $request){

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

            $total_data = UserGroup::select('id')
                ->where('delete_status',0)
                ->get();

            $filter_data = UserGroup::select('id')
                ->where('delete_status',0)
                ->where('group_name','like',"%".$search_value."%")
                ->orWhere('group_code','like',"%".$search_value."%")
                ->orWhere('group_title','like',"%".$search_value."%")
                ->get();

            $data = UserGroup::select('id','group_logo','group_icon','group_name','group_title','group_code','status','id as action')
                ->where('delete_status',0)
                ->where('group_name','like',"%".$search_value."%")
                ->orWhere('group_code','like',"%".$search_value."%")
                ->orWhere('group_title','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get()->toArray();
        }
        else{

            $filter_data = UserGroup::select('id')
                ->where('delete_status',0)
                ->get();

            $data = UserGroup::select('id','group_logo','group_icon','group_name','group_title','group_code','status','id as action')
                ->where('delete_status',0)
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get()->toArray();

            $total_data = $filter_data;
        }

        $total_records = count($total_data);
        $total_records_with_filer = count($filter_data);

        $response['draw'] = $draw;
        $response['iTotalRecords'] = $total_records;
        $response['iTotalDisplayRecords'] = $total_records_with_filer;
        $response['aaData'] = $data;
        $response['csrf_token'] = $csrf_token;

        echo json_encode($response);

    }
}
