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

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
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
            $user_config_data['system_bg_image']=$data->system_bg_image;
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
            $user_config_data['system_bg_image']='';
        }

        return view('admin.system_setting.user_config_info',compact('user_config_data'));
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
            'employee_user_group'  => 'required'
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

        if($data==''){

            $data = new UserConfig;

            $data->add_by = $user_id;
        }
        else{

            $data->edit_by = $user_id;
            $data->edit_status = 1;
            $data->updated_at = now();
        }

        $data->normal_user_type = $request->normal_user_type;
        $data->owner_user_type = $request->owner_user_type;
        $data->tenant_user_type = $request->tenant_user_type;
        $data->employee_user_type = $request->employee_user_type;
        $data->tenant_user_group = $request->tenant_user_group;
        $data->normal_user_group = $request->normal_user_group;
        $data->owner_user_group = $request->owner_user_group;
        $data->employee_user_group = $request->employee_user_group;

        $data->save();

        if($data==true){

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
                'message'=> "user Config Data Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}
