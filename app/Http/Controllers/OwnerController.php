<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserRight;
use DB;

class OwnerController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function owner_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.owner.owner_add',compact('menu_data'));
    }

    public function owner_store(Request $request){

        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:30',
            'email'             => 'required|email|unique:users,email',
            'mobile'            => 'required|string|unique:users,mobile',
            'date_of_birth'     => 'required|string|max:10',
            'sex'               => 'required',
            'blood_group'       => 'required',
            'group'             => 'required|string|max:250',
            'user_type'         => 'required',
            'department'        => 'required',
            'assign_department' => 'required',
            'designation'       => 'required',
            'address'           => 'required|string|max:250',
            'user_photo'        => 'required'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('email','users', $request->email, 0);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate E-mail Address Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('mobile','users', $request->mobile, 0);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Mobile Number Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        $user_config_data = $this->common->get_user_config_data();

        DB::beginTransaction();

        $token = rand(1000,9999);

        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        $password = substr(str_shuffle($str_result),0, 8);

        $data = new User;

        $now = now();

        $data->name = $request->name;
        $data->email = $request->email;
        $data->mobile = $request->mobile;
        $data->date_of_birth = $request->date_of_birth;
        $data->sex = $request->sex;
        $data->blood_group = $request->blood_group;
        $data->group = $request->group;
        $data->user_type = $user_config_data['owner_user_type'];
        $data->department = $request->department;
        $data->assign_department = $request->assign_department;
        $data->designation = $request->designation;
        $data->address = $request->address;
        $data->details = $request->details;
        $data->add_by = $user_id;
        $data->remember_token = $token;
        $data->password_change_status = 0;
        $data->password = Hash::make($password);
        $data->created_at = $now;

        if ($request->hasFile('user_photo')) {

            $file = $request->file('user_photo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->name);

            $fileName = $title_name.'_owner_'.time().'.'.$extension;

            Image::make($file)->resize(500,500)->save('uploads/user/'.$fileName);

            $data->user_photo = $fileName;
        }

        $data->save();

        if($data==true){

            $group_right_data = $this->common->get_group_right($data->group);

            if(!empty($group_right_data)){

                $right_arr = array();

                $sl = 0;

                foreach($group_right_data as $right_g_id=>$right_group){

                    foreach($right_group as $right_c_id=>$right_cat){

                        foreach($right_cat as $right_id=>$right){

                            $right_arr[$sl]['user_id'] = $data->id;
                            $right_arr[$sl]['g_id'] = $right_g_id;
                            $right_arr[$sl]['c_id'] = $right_c_id;
                            $right_arr[$sl]['r_id'] = $right_id;
                            $right_arr[$sl]['add_by'] = $user_id;
                            $right_arr[$sl]['created_at'] = $now;

                            $sl++;
                        }
                    }
                }

                if(!empty($right_arr)){

                    UserRight::where('user_id', $data->id)->delete();

                    UserRight::insert($right_arr);
                }
            }

            $encrypt_data = $this->common->encrypt_data($data->id);

            $mail_data = new MailController();

            $status = $mail_data->sent_email_verify_email_with_password($request->email,$encrypt_data,$request->name,$password);

            if($status==true){

                DB::commit();

                $notification = array(
                    'message'=> "Owner Details Created Successfully",
                    'alert_type'=>'success',
                    'create_status'=>1,
                    'user_id' =>$encrypt_data,
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Owner Details Does Not Created Successfully",
                    'alert_type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>'',
                );
            }

        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Owner Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function owner_edit_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.owner.owner_edit',compact('menu_data'));
    }

    public function owner_grid(Request $request){

        $menu_data = $this->common->get_page_menu_grid('user_management.owner.add');

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

            $total_data = DB::table('users as a')
                ->join('user_groups as b', 'b.id', '=', 'a.group')
                ->select('a.id')
                ->where('a.user_type',$user_config_data['owner_user_type'])
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->get();

            $filter_data = DB::table('users as a')
                ->join('user_groups as b', 'b.id', '=', 'a.group')
                ->select('a.id')
                ->where('a.user_type',$user_config_data['owner_user_type'])
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('a.name','like',"%".$search_value."%")
                ->orWhere('b.group_name','like',"%".$search_value."%")
                ->orWhere('a.email','like',"%".$search_value."%")
                ->orWhere('a.mobile','like',"%".$search_value."%")
                ->get();

            $data = DB::table('users as a')
                ->join('user_groups as b', 'b.id', '=', 'a.group')
                ->select('a.id  as user_id', 'a.name', 'a.user_photo', 'b.group_name', 'a.email', 'a.mobile', 'a.status')
                ->where('a.user_type',$user_config_data['owner_user_type'])
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('a.name','like',"%".$search_value."%")
                ->orWhere('b.group_name','like',"%".$search_value."%")
                ->orWhere('a.email','like',"%".$search_value."%")
                ->orWhere('a.mobile','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = DB::table('users as a')
                ->join('user_groups as b', 'b.id', '=', 'a.group')
                ->select('a.id')
                ->where('a.user_type',$user_config_data['owner_user_type'])
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->get();

            $data = DB::table('users as a')
                ->join('user_groups as b', 'b.id', '=', 'a.group')
                ->select('a.id  as user_id', 'a.name', 'a.user_photo', 'b.group_name', 'a.email', 'a.mobile', 'a.status')
                ->where('a.user_type',$user_config_data['owner_user_type'])
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

            $record_data[$sl]['user_id'] = $value->user_id;
            $record_data[$sl]['sl'] = $sl_start;
            $record_data[$sl]['user_photo'] = $value->user_photo;
            $record_data[$sl]['name'] = $value->name;
            $record_data[$sl]['group_name'] = $value->group_name;
            $record_data[$sl]['email'] = $value->email;
            $record_data[$sl]['mobile'] = $value->mobile;
            $record_data[$sl]['status'] = $value->status;
            $record_data[$sl]['action'] = $value->user_id;
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

    public function owner_single_edit_page($id){

        $user_data = User::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('user_management.owner.add****user_management.owner.edit');

        return view('admin.owner.owner_edit_view',compact('menu_data','user_data','user_right_data'));
    }

    public function owner_update($update_id, Request $request){

          $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:30',
            'email'             => 'required|email',Rule::unique('users')->ignore($request->update_id),
            'mobile'            => 'required|string',Rule::unique('users')->ignore($request->update_id),
            'date_of_birth'     => 'required|string|max:10',
            'sex'               => 'required',
            'blood_group'       => 'required',
            'group'             => 'required|string|max:250',
            'user_type'         => 'required',
            'department'        => 'required',
            'assign_department' => 'required',
            'designation'       => 'required',
            'address'           => 'required|string|max:250',
            'user_photo' => $request->input('hidden_user_photo') === '' ? 'required' : ''
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

        $duplicate_status_1 = $this->common->get_duplicate_value('email','users', $request->email,$request->update_id);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate E-mail Address Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('mobile','users', $request->mobile,$request->update_id);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Mobile Number Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        $user_config_data = $this->common->get_user_config_data();

        $now = now();

        DB::beginTransaction();

        $data = User::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->name = $request->name;
        $data->email = $request->email;
        $data->mobile = $request->mobile;
        $data->date_of_birth = $request->date_of_birth;
        $data->sex = $request->sex;
        $data->blood_group = $request->blood_group;
        $data->group = $request->group;
        $data->user_type = $user_config_data['owner_user_type'];
        $data->department = $request->department;
        $data->assign_department = $request->assign_department;
        $data->designation = $request->designation;
        $data->address = $request->address;
        $data->details = $request->details;
        $data->edit_by = $user_id;
        $data->updated_at = $now;

        if ($request->hasFile('user_photo')) {

            $file = $request->file('user_photo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->name);

            $fileName = $title_name.'_owner_'.time().'.'.$extension;

            Image::make($file)->resize(500,500)->save('uploads/user/'.$fileName);

            if($data->user_photo!='')
            {
                $deletePhoto = "uploads/user/".$data->user_photo;
                
                if(file_exists($deletePhoto)){

                    unlink($deletePhoto);
                }
            }

            $data->user_photo = $fileName;
        }

        $data->save();

        if($data==true){

            $group_right_data = $this->common->get_group_right($data->group);

            if(!empty($group_right_data)){

                $right_arr = array();

                $sl = 0;

                foreach($group_right_data as $right_g_id=>$right_group){

                    foreach($right_group as $right_c_id=>$right_cat){

                        foreach($right_cat as $right_id=>$right){

                            $right_arr[$sl]['user_id'] = $data->id;
                            $right_arr[$sl]['g_id'] = $right_g_id;
                            $right_arr[$sl]['c_id'] = $right_c_id;
                            $right_arr[$sl]['r_id'] = $right_id;
                            $right_arr[$sl]['add_by'] = $user_id;
                            $right_arr[$sl]['created_at'] = $now;

                            $sl++;
                        }
                    }
                }

                if(!empty($right_arr)){

                    UserRight::where('user_id', $data->id)->delete();

                    UserRight::insert($right_arr);
                }
            }

            DB::commit();

            $notification = array(
                'message'=> "Owner Details Updated Successfully",
                'alert_type'=>'success',
                'create_status'=>1,
                'user_id' =>$request->update_id,
            );;
            
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Owner Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function owner_delete_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.owner.owner_delete',compact('menu_data'));
    }

    public function owner_delete($id){

        $notification = array();

        $data = User::where('delete_status',0)
            ->where('id',$id)
            ->first();

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        if(empty($data)){

            $notification = array(
                'message'=> "Owner Data Not Found!!!",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }
        else if($data->id==$user_id){

            $notification = array(
                'message'=> "You Can Not Delete Your Own Data!!!",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::beginTransaction();

            $data->delete_by = $user_id;
            $data->delete_status = 1;
            $data->deleted_at = now();

            $data->save();

            if($data==true){

                DB::commit();

                $notification = array(
                    'message'=> "Owner Details Deleted Successfully",
                    'alert_type'=>'success',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Owner Details Not Deleted Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }

        $menu_data = $this->common->get_page_menu();

        return view('admin.owner.owner_delete_alert',compact('menu_data','notification'));
    }

    public function owner_view_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.owner.owner_view',compact('menu_data'));
    }

    public function owner_single_view_page($id){

        $user_data = User::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('user_management.owner.add****user_management.owner.view');

        return view('admin.owner.owner_single_view',compact('menu_data','user_data','user_right_data'));
    }

    public function owner_right_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.owner.owner_right',compact('menu_data'));
    }

    public function owner_right_setup_page($id){

        $user_data = User::where('delete_status',0)
            ->where('id',$id)
            ->first();

        $menu_data = $this->common->get_page_menu();

        $all_right_data = $this->common->get_all_right();

        $user_right_data = $this->common->get_page_menu_single_view('user_management.owner.add****user_management.owner.right');

        $right_data = $this->common->get_user_right($user_data->id);

        return view('admin.owner.owner_right_setup',compact('menu_data','user_data','user_right_data','all_right_data','right_data'));
    }

    public function owner_right_store ($id, Request $request){

        $right_arr = array();
        $sl=0;

        $max_group = $request->g_id_max;

        $user_id = $request->user_id;

        $user_session_data = session()->all();

        $add_by = $user_session_data[config('app.app_session_name')]['id'];

        $now = now();

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

                        $right_arr[$sl]['user_id'] = $user_id;
                        $right_arr[$sl]['g_id'] = $g_id;
                        $right_arr[$sl]['c_id'] = $c_id;
                        $right_arr[$sl]['r_id'] = $r_id;
                        $right_arr[$sl]['add_by'] = $add_by;
                        $right_arr[$sl]['created_at'] = $now;

                        $sl++;
                    }
                }
            }
        }

        $notification = array();

        if(!empty($right_arr)){

            DB::beginTransaction();

            UserRight::where('user_id', $user_id)->delete();

            $status = UserRight::insert($right_arr);

            if($status==true){

                DB::commit();

                $notification = array(
                    'message'=> "Owner Right Created Successfully",
                    'alert_type'=>'success',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Owner Right Not Created Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Not Data Found!!!",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}
