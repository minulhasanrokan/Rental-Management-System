<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemDetails;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class SystemSettingController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function system_setting_page(){

        $data = SystemDetails::where('delete_status',0)
            ->where('status',1)
            ->where('id',1)
            ->first();

        $system_data = array();

        if($data!=''){

            $system_data['system_name']=$data->system_name;
            $system_data['system_email']=$data->system_email;
            $system_data['system_mobile']=$data->system_mobile;
            $system_data['system_title']=$data->system_title;
            $system_data['system_address']=$data->system_address;
            $system_data['system_copy_right']=$data->system_copy_right;
            $system_data['system_deatils']=$data->system_deatils;
            $system_data['system_logo']=$data->system_logo;
            $system_data['system_favicon']=$data->system_favicon;
            $system_data['add_by']=$data->add_by;
            $system_data['edit_by']=$data->edit_by;
            $system_data['delete_by']=$data->delete_by;
            $system_data['id']=$data->id;
            $system_data['edit_status']=$data->edit_status;
            $system_data['delete_status']=$data->delete_status;
            $system_data['system_bg_image']=$data->system_bg_image;
        }
        else{
            $system_data['system_name']='';
            $system_data['system_email']='';
            $system_data['system_mobile']='';
            $system_data['system_title']='';
            $system_data['system_address']='';
            $system_data['system_copy_right']='';
            $system_data['system_deatils']='';
            $system_data['system_logo']='';
            $system_data['system_favicon']='';
            $system_data['add_by']='';
            $system_data['edit_by']='';
            $system_data['delete_by']='';
            $system_data['id']='';
            $system_data['edit_status']='';
            $system_data['delete_status']='';
            $system_data['system_bg_image']='';
        }

        return view('admin.system_setting.system_info',compact('system_data'));
    }

    public function system_information_update(Request $request){

        $validator = Validator::make($request->all(), [
            'system_name' => 'required|string|max:250',
            'system_email' => 'required|email|max:250',
            'system_mobile' => 'required|string|max:20',
            'system_title' => 'required|string|max:250',
            'system_copy_right' => 'required|string|max:250',
            'system_logo' => $request->input('hidden_system_logo') === '' ? 'required' : '',
            'system_favicon' => $request->input('hidden_system_favicon') === '' ? 'required' : '',
            'system_bg_image' => $request->input('hidden_system_bg_image') === '' ? 'required' : '',

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

        $data = SystemDetails::where('delete_status',0)
            ->where('status',1)
            ->where('id',1)
            ->first();

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        if($data==''){

            $data = new SystemDetails;

            $data->add_by = $user_id;
        }
        else{

            $data->edit_by = $user_id;
            $data->edit_status = 1;
            $data->updated_at = now();
        }

        $data->system_name = $request->system_name;
        $data->system_email = $request->system_email;
        $data->system_mobile = $request->system_mobile;
        $data->system_title = $request->system_title;
        $data->system_address = $request->system_address;
        $data->system_copy_right = $request->system_copy_right;
        $data->system_deatils = $request->system_deatils;

        if ($request->hasFile('system_logo')) {

            $file = $request->file('system_logo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->system_name);

            $fileName = $title_name.'_logo_'.time().'.'.$extension;

            Image::make($file)->resize(500,500)->save('uploads/logo/'.$fileName);

            if($data->system_logo!='')
            {
                $deletePhoto = "uploads/logo/".$data->system_logo;
                
                if(file_exists($deletePhoto)){

                    unlink($deletePhoto);
                }
            }

            $data->system_logo = $fileName;
        }

        if ($request->hasFile('system_bg_image')) {

            $file = $request->file('system_bg_image');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->system_name);

            $fileName = $title_name.'_bg_'.time().'.'.$extension;

            Image::make($file)->resize(1920,1080)->save('uploads/login_bg/'.$fileName);

            if($data->system_bg_image!='')
            {
                $deletePhoto = "uploads/login_bg/".$data->system_bg_image;
                
                if(file_exists($deletePhoto)){

                    unlink($deletePhoto);
                }
            }

            $data->system_bg_image = $fileName;
        }

        if ($request->hasFile('system_favicon')) {

            $file = $request->file('system_favicon');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->system_name);

            $fileName = $title_name.'_icon_'.time().'.'.$extension;

            Image::make($file)->resize(100,100)->save('uploads/icon/'.$fileName);

            if($data->system_favicon!='')
            {
                $deletePhoto = "uploads/icon/".$data->system_favicon;
                
                if(file_exists($deletePhoto)){

                    unlink($deletePhoto);
                }
            }

            $data->system_favicon = $fileName;
        }

        $data->save();

        if($data==true){

            $notification = array(
                'message'=> "System Details Created Successfully",
                'alert_type'=>'info',
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "System Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}
