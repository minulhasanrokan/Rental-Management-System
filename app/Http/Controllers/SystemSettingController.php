<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemDetails;
use App\Models\MailSetup;
use App\Models\SmsApi;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DB;

class SystemSettingController extends Controller
{
    public $common;

    public $app_session_name ='';

    public $header_status = 0;

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');

        $this->header_status = $this->common->check_header_info();
    }

    public function system_setting_page(){

        $data = SystemDetails::where('delete_status',0)
            ->where('status',1)
            //->where('id',1)
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

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.system_setting.system_info',compact('system_data'));
        }
        else{

            return view('admin.system_setting.system_info_master',compact('system_data'));
        }
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

        DB::beginTransaction();

        $data = SystemDetails::where('delete_status',0)
            ->where('status',1)
            //->where('id',1)
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

            DB::commit();

            $notification = array(
                'message'=> "System Details Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "System Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function system_mail_page(){

        $data = MailSetup::where('delete_status',0)
            ->where('status',1)
            //->where('id',1)
            ->first();

        $mail_data = array();

        if($data!=''){

            $password = $this->common->decrypt_data($data->email_password);

            $mail_data['email_id']=$data->email_id;
            $mail_data['email_host']=$data->email_host;
            $mail_data['email_mailer']=$data->email_mailer;
            $mail_data['email_port']=$data->email_port;
            $mail_data['email_username']=$data->email_username;
            $mail_data['email_password']=$password;
            $mail_data['email_encription']=$data->email_encription;
            $mail_data['email_name']=$data->email_name;
            $mail_data['add_by']=$data->add_by;
            $mail_data['edit_by']=$data->edit_by;
            $mail_data['delete_by']=$data->delete_by;
            $mail_data['id']=$data->id;
            $mail_data['edit_status']=$data->edit_status;
            $mail_data['delete_status']=$data->delete_status;
            $mail_data['system_url']=$data->system_url;
            $mail_data['email_driver']=$data->email_driver;
        }
        else{
            $mail_data['email_id']='';
            $mail_data['email_host']='';
            $mail_data['email_mailer']='';
            $mail_data['email_port']='';
            $mail_data['email_username']='';
            $mail_data['email_password']='';
            $mail_data['email_encription']='';
            $mail_data['email_name']='';
            $mail_data['add_by']='';
            $mail_data['edit_by']='';
            $mail_data['delete_by']='';
            $mail_data['id']='';
            $mail_data['edit_status']='';
            $mail_data['delete_status']='';
            $mail_data['system_url']='';
            $mail_data['email_driver']='';
        }

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.system_setting.mail_setup',compact('mail_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.system_setting.mail_setup_master',compact('mail_data','system_data'));
        }
    }

    public function system_mail_update(Request $request){

        $validator = Validator::make($request->all(), [
            'email_id' => 'required|email|max:250',
            'email_host' => 'required|string|max:250',
            'email_mailer' => 'required|string|max:250',
            'email_port' => 'required|string|max:10',
            'email_username' => 'required|string|max:250',
            'email_password' => 'required|string|max:250',
            'email_name' => 'required|string|max:250',
            'system_url' => 'required|string|max:250',
            'email_driver' => 'required|string|max:250',
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

        $data = MailSetup::where('delete_status',0)
            ->where('status',1)
            //->where('id',1)
            ->first();

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        if($data==''){

            $data = new MailSetup;

            $data->add_by = $user_id;
        }
        else{

            $data->edit_by = $user_id;
            $data->edit_status = 1;
            $data->updated_at = now();
        }

        $password = $this->common->encrypt_data($request->email_password);

        $data->email_id = $request->email_id;
        $data->email_host = $request->email_host;
        $data->email_mailer = $request->email_mailer;
        $data->email_port = $request->email_port;
        $data->email_username = $request->email_username;
        $data->email_password = $password;
        $data->email_encription = $request->email_encription;
        $data->email_name = $request->email_name;
        $data->system_url = $request->system_url;
        $data->email_driver = $request->email_driver;

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "System E-mail Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "System E-mail Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function system_sms_page(){

        $data = SmsApi::where('delete_status',0)
            ->where('status',1)
            //->where('id',1)
            ->first();

        $sms_data = array();

        if($data!=''){

            $password = $this->common->decrypt_data($data->api_password);

            $sms_data['api_token']=$data->api_token;
            $sms_data['api_url']=$data->api_url;
            $sms_data['api_mobile']=$data->api_mobile;
            $sms_data['api_username']=$data->api_username;
            $sms_data['api_password']=$password;
            $sms_data['system_url']=$data->system_url;
            $sms_data['add_by']=$data->add_by;
            $sms_data['edit_by']=$data->edit_by;
            $sms_data['delete_by']=$data->delete_by;
            $sms_data['id']=$data->id;
            $sms_data['edit_status']=$data->edit_status;
            $sms_data['delete_status']=$data->delete_status;
        }
        else{
            $sms_data['api_token']='';
            $sms_data['api_url']='';
            $sms_data['api_mobile']='';
            $sms_data['api_username']='';
            $sms_data['api_password']='';
            $sms_data['system_url']='';
            $sms_data['add_by']='';
            $sms_data['edit_by']='';
            $sms_data['delete_by']='';
            $sms_data['id']='';
            $sms_data['edit_status']='';
            $sms_data['delete_status']='';
        }

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.system_setting.sms_setup',compact('sms_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.system_setting.sms_setup_master',compact('sms_data','system_data'));
        }
    }

    public function system_sms_update (Request $request){

        $validator = Validator::make($request->all(), [
            'api_token' => 'required|string|max:250',
            'api_url' => 'required|string|max:250',
            'api_mobile' => 'required|string|max:250',
            'api_username' => 'required|string|max:250',
            'api_password' => 'required|string|max:250',
            'system_url' => 'required|string|max:250',
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

        $data = SmsApi::where('delete_status',0)
            ->where('status',1)
            //->where('id',1)
            ->first();

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        if($data==''){

            $data = new SmsApi;

            $data->add_by = $user_id;
        }
        else{

            $data->edit_by = $user_id;
            $data->edit_status = 1;
            $data->updated_at = now();
        }

        $password = $this->common->encrypt_data($request->api_password);

        $data->api_token = $request->api_token;
        $data->api_url = $request->api_url;
        $data->api_mobile = $request->api_mobile;
        $data->api_username = $request->api_username;
        $data->api_password = $password;
        $data->system_url = $request->system_url;

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "SMS API Details Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "SMS API Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}
