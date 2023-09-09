<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use DB;

class UserController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }
    
    public function user_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.user.user_add',compact('menu_data'));
    }

    public function user_store(Request $request){

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

        DB::beginTransaction();

        $token = rand(1000,9999);

        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        $password = substr(str_shuffle($str_result),0, 8);

        $data = new User;

        $data->name = $request->name;
        $data->email = $request->email;
        $data->mobile = $request->mobile;
        $data->date_of_birth = $request->date_of_birth;
        $data->sex = $request->sex;
        $data->blood_group = $request->blood_group;
        $data->group = $request->group;
        $data->user_type = $request->user_type;
        $data->department = $request->department;
        $data->assign_department = $request->assign_department;
        $data->designation = $request->designation;
        $data->address = $request->address;
        $data->details = $request->details;
        $data->add_by = $user_id;
        $data->remember_token = $token;
        $data->password_change_status = 0;
        $data->password = Hash::make($password);
        $data->created_at = now();

        if ($request->hasFile('user_photo')) {

            $file = $request->file('user_photo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->name);

            $fileName = $title_name.'_user_'.time().'.'.$extension;

            Image::make($file)->resize(500,500)->save('uploads/user/'.$fileName);

            $data->user_photo = $fileName;
        }

        $data->save();

        if($data==true){

            $encrypt_data = $this->common->encrypt_data($data->id);

            $mail_data = new MailController();

            $status = $mail_data->sent_email_verify_email_with_password($request->email,$encrypt_data,$request->name,$password);

            if($status==true){

                DB::commit();

                $notification = array(
                    'message'=> "User Details Created Successfully",
                    'alert-type'=>'info',
                    'create_status'=>1,
                    'user_id' =>$encrypt_data,
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "User Details Does Not Created Successfully",
                    'alert-type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>'',
                );
            }

        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "User Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}
