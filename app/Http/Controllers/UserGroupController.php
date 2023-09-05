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

        $menu_data = $this->common->get_page_menu_grid('user_management.user_group.add');

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

            $data = UserGroup::select('id','group_logo','group_icon','group_name','group_title','group_code','status')
                ->where('delete_status',0)
                ->where('group_name','like',"%".$search_value."%")
                ->orWhere('group_code','like',"%".$search_value."%")
                ->orWhere('group_title','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = UserGroup::select('id')
                ->where('delete_status',0)
                ->get();

            $data = UserGroup::select('id','group_logo','group_icon','group_name','group_title','group_code','status')
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
            $record_data[$sl]['group_logo'] = $value->group_logo;
            $record_data[$sl]['group_icon'] = $value->group_icon;
            $record_data[$sl]['group_name'] = $value->group_name;
            $record_data[$sl]['group_title'] = $value->group_title;
            $record_data[$sl]['group_code'] = $value->group_code;
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

    public function user_group_single_view_page($id){

        $group_data = UserGroup::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('user_management.user_group.add****user_management.user_group.view');

        return view('admin.user_group.group_single_view',compact('menu_data','group_data','user_right_data'));
    }

    public function user_group_edit_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.user_group.group_edit',compact('menu_data'));
    }

    public function user_group_single_edit_page($id){


        $group_data = UserGroup::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('user_management.user_group.add****user_management.user_group.edit');

        return view('admin.user_group.group_edit_view',compact('menu_data','group_data','user_right_data'));
    }

    public function user_group_update($update_id, Request $request){

        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|max:250',
            'group_code' => 'required|string|max:20',
            'group_title' => 'required|string|max:250',
            'group_logo' => $request->input('hidden_group_logo') === '' ? 'required' : '',
            'group_icon' => $request->input('hidden_group_icon') === '' ? 'required' : ''
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

        $duplicate_status_1 = $this->common->get_duplicate_value('group_name','user_groups', $request->group_name, $request->update_id);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Group Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('group_code','user_groups', $request->group_code, $request->update_id);

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

        $data = UserGroup::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->group_name = $request->group_name;
        $data->group_code = $request->group_code;
        $data->group_title = $request->group_title;
        $data->group_deatils = $request->group_deatils;
        $data->edit_by = $user_id;
        $data->edit_status = 1;
        $data->updated_at = now();

        if ($request->hasFile('group_logo')) {

            $file = $request->file('group_logo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->group_name);

            $fileName = $title_name.'_logo_'.time().'.'.$extension;

            Image::make($file)->resize(500,500)->save('uploads/user_group/'.$fileName);

            if($data->group_logo!='')
            {
                $deletePhoto = "uploads/user_group/".$data->group_logo;
                
                if(file_exists($deletePhoto)){

                    unlink($deletePhoto);
                }
            }

            $data->group_logo = $fileName;
        }

        if ($request->hasFile('group_icon')) {

            $file = $request->file('group_icon');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->group_name);

            $fileName = $title_name.'_icon_'.time().'.'.$extension;

            Image::make($file)->resize(100,100)->save('uploads/user_group/'.$fileName);

            if($data->group_icon!='')
            {
                $deletePhoto = "uploads/user_group/".$data->group_icon;
                
                if(file_exists($deletePhoto)){

                    unlink($deletePhoto);
                }
            }

            $data->group_icon = $fileName;
        }

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "User Group Details Updated Successfully",
                'alert_type'=>'info',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "User Group Details Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);

    }

    public function user_group_delete_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.user_group.group_delete',compact('menu_data'));
    }

    public function user_group_delete($id){

        $notification = array();

        $data = UserGroup::where('delete_status',0)
            ->where('id',$id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "User Data Not Found!!!",
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
                    'message'=> "User Group Details Deleted Successfully",
                    'alert_type'=>'info',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "User Group Details Not Deleted Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }

        $menu_data = $this->common->get_page_menu();

        return view('admin.user_group.group_delete_alert',compact('menu_data','notification'));
    }

    public function user_group_right_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.user_group.group_right',compact('menu_data'));
    }

    public function user_group_right_setup_page($id){

        $group_data = UserGroup::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $all_right_data = $this->common->get_all_right();

        $user_right_data = $this->common->get_page_menu_single_view('user_management.user_group.add****user_management.user_group.right');

        return view('admin.user_group.group_right_setup',compact('menu_data','group_data','user_right_data','all_right_data'));
    }

    public function user_group_right_store ($id, Request $request){

        $right_arr = array();
        $sl=0;

        $max_group = $request->g_id_max;

        for($i=1; $i<=$max_group; $i++){

            $g_id_name = 'g_id_'.$i;

            $g_id = $request->$g_id_name;

            $cat_id = 'c_id_max_'.$i;

            $max_cat = $request->$cat_id;

            for($j=1; $j<=$max_cat; $j++){

                $c_id_name = 'c_id_'.$i."_".$j;

                $c_id = $request->$c_id_name;

                $r_id = 'r_id_max_'.$i."_".$j;

                $max_r_id = $request->$r_id;

                for($k=1; $k<=$max_r_id; $k++){

                    $r_status_name = 'r_id_checkbox_'.$i."_".$j."_".$k;
                    $r_status = $request->$r_status_name;

                    if($r_status==1){

                        $r_id_name = 'r_id_'.$i."_".$j."_".$k;

                        $r_id = $request->$r_id_name;

                        $right_arr[$sl]['g_id'] = $g_id;
                        $right_arr[$sl]['c_id'] = $c_id;
                        $right_arr[$sl]['r_id'] = $r_id;

                        $sl++;
                    }
                }
            }
        }
    }
}
