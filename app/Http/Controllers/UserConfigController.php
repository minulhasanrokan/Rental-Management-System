<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserConfig;
use Illuminate\Support\Facades\Validator;
use DB;

class UserConfigController extends Controller
{
    public $common;

    public $app_session_name ='';

    public $header_status = 0;

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');

        $this->header_status = $this->common->check_header_info();
    }

    public function user_config_page(){

        $data = UserConfig::where('delete_status',0)
            ->where('status',1)
            //->where('id',1)
            ->first();

        $user_config_data = array();

        if($data!=''){

            $user_config_data['normal_user_type']=$data->normal_user_type;
            $user_config_data['owner_user_type']=$data->owner_user_type;
            $user_config_data['tenant_user_type']=$data->tenant_user_type;
            $user_config_data['employee_user_type']=$data->employee_user_type;
            $user_config_data['tenant_user_group']=$data->tenant_user_group;
            $user_config_data['normal_user_group']=$data->normal_user_group;
            $user_config_data['owner_user_group']=$data->owner_user_group;
            $user_config_data['employee_user_group']=$data->employee_user_group;
            $user_config_data['add_by']=$data->add_by;
            $user_config_data['edit_by']=$data->edit_by;
            $user_config_data['delete_by']=$data->delete_by;
            $user_config_data['id']=$data->id;
            $user_config_data['edit_status']=$data->edit_status;
            $user_config_data['delete_status']=$data->delete_status;
            $user_config_data['session_time']=$data->session_time;
            $user_config_data['session_check_time']=$data->session_check_time;
        }
        else{
            $user_config_data['normal_user_type']='';
            $user_config_data['owner_user_type']='';
            $user_config_data['tenant_user_type']='';
            $user_config_data['employee_user_type']='';
            $user_config_data['tenant_user_group']='';
            $user_config_data['normal_user_group']='';
            $user_config_data['owner_user_group']='';
            $user_config_data['employee_user_group']='';
            $user_config_data['add_by']='';
            $user_config_data['edit_by']='';
            $user_config_data['delete_by']='';
            $user_config_data['id']='';
            $user_config_data['edit_status']='';
            $user_config_data['delete_status']='';
            $user_config_data['session_time']='';
            $user_config_data['session_check_time']='';
        }

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.system_setting.user_config_info',compact('user_config_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.system_setting.user_config_info_master',compact('user_config_data','system_data'));
        }
    }

    public function user_config_update(Request $request){

        $validator = Validator::make($request->all(), [
            'normal_user_type'  => 'required',
            'owner_user_type'   => 'required',
            'tenant_user_type'  => 'required',
            'employee_user_type'  => 'required',
            'tenant_user_group'  => 'required',
            'normal_user_group'  => 'required',
            'owner_user_group'  => 'required',
            'employee_user_group'  => 'required',
            'session_time'  => 'required',
            'session_check_time' => 'required'
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

        DB::beginTransaction();

        $data = UserConfig::where('delete_status',0)
            ->where('status',1)
            //->where('id',1)
            ->first();

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        $activity = '';

        if($data==''){

            $data = new UserConfig;

            $data->add_by = $user_id;

            $activity = 'Add User Config Information';
        }
        else{

            $data->edit_by = $user_id;
            $data->edit_status = 1;
            $data->updated_at = now();

            $activity = 'Edit User Config Information';
        }

        $data->normal_user_type = $request->normal_user_type;
        $data->owner_user_type = $request->owner_user_type;
        $data->tenant_user_type = $request->tenant_user_type;
        $data->employee_user_type = $request->employee_user_type;
        $data->tenant_user_group = $request->tenant_user_group;
        $data->normal_user_group = $request->normal_user_group;
        $data->owner_user_group = $request->owner_user_group;
        $data->employee_user_group = $request->employee_user_group;
        $data->session_time = $request->session_time;
        $data->session_check_time = $request->session_check_time;

        $data->save();

        if($data==true){

            $status = $this->common->update_user_session_time($data->session_check_time);

            if($status==1){

                $status = $this->common->add_user_activity_history('user_configs',$data->id,$activity);

                if($status==1){

                    DB::commit();

                    $notification = array(
                        'message'=> "user Config Data Created Successfully",
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
                    'message'=> "Something Went Wrong Try Again",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "user Config Data Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}