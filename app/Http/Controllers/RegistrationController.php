<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SystemDetails;
use Illuminate\Support\Facades\Hash;
use DB;

class RegistrationController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function registration_page(){

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

        return view('admin.registration_page',compact('system_data'));
    }

    public function registration_store(Request $request){

        $request->validate(
            [
                'name'              =>      'required|string|max:30',
                'email'             =>      'required|email|unique:users,email',
                'mobile'            =>      'required|string|unique:users,mobile',
                'password'          =>      'required|alpha_num|min:6|max:10',
                'confirm_password'  =>      'required|same:password'
            ]
        );

        $token = rand(1000,9999);

        DB::beginTransaction();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'group' => '1',
            'remember_token' => $token,
        ]);

        if($user==true){

            $encrypt_data = $this->common->encrypt_data($user->id);

            $mail_data = new MailController();

            $status = $mail_data->sent_email_verify_email($request->email,$encrypt_data,$request->name);

            if($status==true){

                DB::commit();

                $notification = array(
                    'message'=> "User Account Created Successfully",
                    'alert-type'=>'info',
                    'create_status'=>1,
                    'user_id' =>$encrypt_data,
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "User Account Does Not Created Successfully",
                    'alert-type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>'',
                );
            }
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "User Account Does Not Created Successfully",
                'alert-type'=>'warning',
                'create_status'=>0,
                'user_id' =>'',
            );
        }

        return redirect()->back()->with($notification);
    }

    public function resend_verify_email($token){

        $notification = array();

        $user_id = $this->common->decrypt_data($token);

        $data = User::where('delete_status',0)
            ->where('id',$user_id)
            ->first();

        if($data->status==0 && !empty($data)){

            $notification = array(
                'message'=> "Your Account Was Deactivated. Please Contact With Adminstrator To Active Your Account.",
                'alert_type'=>'warning',
                'create_status'=>0,
                'user_id' =>'',
            );
        }
        else if($data->verify_status==1 || empty($data)){

            $notification = array(
                'message'=> "Invalid Url",
                'alert_type'=>'warning',
                'create_status'=>0,
                'user_id' =>'',
            );
        }
        else{

            $mail_data = new MailController();

            $status = $mail_data->sent_email_verify_email($data->email,$token,$data->name);

            if($status==true){

                $notification = array(
                    'message'=> "A Verification Link Sent To Your E-mail Account",
                    'alert-type'=>'info',
                    'create_status'=>1,
                    'verify_mail'=>1,
                    'user_id' =>$token,
                );
            }
            else{

                $notification = array(
                    'message'=> "Something Went Wrong",
                    'alert-type'=>'warning',
                    'create_status'=>0,
                    'verify_mail' =>0,
                    'user_id' =>'',
                );
            }
        }

        return redirect()->back()->with($notification);
    }

    public function verify_email_page($token){

        DB::beginTransaction();

        $notification = array();

        $user_id = $this->common->decrypt_data($token);

        $data = User::where('delete_status',0)
            ->where('id',$user_id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "Invalid Url",
                'alert_type'=>'warning',
                'create_status'=>0,
                'user_id' =>'',
            );
        }
        else{

            if ($data->status==0) {

                $notification = array(
                    'message'=> "Your Account Was Deactivated. Please Contact With Adminstrator",
                    'alert_type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>'',
                );
            }
            else if ($data->verify_status==1) {
                
                $notification = array(
                    'message'=> "Invalid Url",
                    'alert_type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>'',
                );
            }
            else{

                $data->verify_status =1;
                $data->email_verified_at =now();

                $data->save();

                if($data==true){

                    DB::commit();

                    $notification = array(
                        'message'=> "Your Account Successfully Verified. Please Login To Access your Account",
                        'alert_type'=>'info',
                        'create_status'=>1,
                        'user_id' =>'',
                    );
                }
                else{

                    DB::rollBack();

                    $notification = array(
                        'message'=> "Something Went Wrong Please Try Again",
                        'alert_type'=>'warning',
                        'create_status'=>0,
                        'user_id' =>'',
                    );
                }
            }

        }

        return view('admin.user_verify',compact('notification'));
    }

    public function forgot_password_page(){

        return view('admin.forgot_password');
    }

    public function forgot_password_link(Request $request){

        $notification = array();

        $request->validate(
            [
                'email'             =>      'required|email'
            ]
        );

        $data = User::where('delete_status',0)
            ->where('email',$request->email)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "No User Account Found",
                'alert_type'=>'warning',
                'create_status'=>0,
                'user_id' =>'',
            );
        }
        else{

            $user_id = $this->common->encrypt_data($data->id);

            if($data->status==0){

                $notification = array(
                    'message'=> "Your Account Was Deactivated. Please Contact With Adminstrator To Active Your Account.",
                    'alert_type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>'',
                );
            }
            else if($data->verify_status!=1){

                $notification = array(
                    'message'=> "Please Verify Your Account First",
                    'alert_type'=>'warning',
                    'create_status'=>1,
                    'verify_mail'=>1,
                    'user_id' =>$user_id,
                );
            }
            else{

                $token = rand(1000,9999);

                $data->remember_token = $token;

                $data->save();

                $status = false;

                if($data==true){

                    $mail_data = new MailController();

                    $status = $mail_data->sent_password_reset_email($request->email,$user_id,$data->name,$token);
                }

                if($status==false){

                    $notification = array(
                        'message'=> "Something Went Wrong",
                        'alert_type'=>'warning',
                        'create_status'=>0,
                        'user_id' =>'',
                    );
                }
                else{

                    $email = $this->common->encrypt_data($request->email);

                    $notification = array(
                        'message'=> "A Password Reset Link Sent To Your E-mail Account",
                        'alert_type'=>'info',
                        'create_status'=>1,
                        'user_id' =>$email,
                    );
                }
            }
        }

        return redirect()->back()->with($notification);
    }

    public function resend_forgot_password($email){

        $email = $this->common->decrypt_data($email);

        $data = User::where('delete_status',0)
            ->where('email',$email)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "No User Account Found",
                'alert_type'=>'warning',
                'create_status'=>0,
                'user_id' =>'',
            );
        }
        else{

            $user_id = $this->common->encrypt_data($data->id);

            if($data->status==0){

                $notification = array(
                    'message'=> "Your Account Was Deactivated. Please Contact With Adminstrator To Active Your Account.",
                    'alert_type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>'',
                );
            }
            else if($data->verify_status!=1){

                $notification = array(
                    'message'=> "Please Verify Your Account First",
                    'alert_type'=>'warning',
                    'create_status'=>1,
                    'verify_mail' =>1,
                    'user_id' =>$user_id,
                );
            }
            else{

                $token = rand(1000,9999);

                $data->remember_token = $token;

                $data->save();

                $status = false;

                if($data==true){

                    $mail_data = new MailController();

                    $status = $mail_data->sent_password_reset_email($data->email,$user_id,$data->name,$token);
                }

                if($status==false){

                    $notification = array(
                        'message'=> "Something Went Wrong",
                        'alert_type'=>'warning',
                        'create_status'=>0,
                        'user_id' =>'',
                    );
                }
                else{

                    $notification = array(
                        'message'=> "A Password Reset Link Sent To Your E-mail Account",
                        'alert_type'=>'info',
                        'create_status'=>1,
                        'user_id' =>$email,
                    );
                }
            }
        }

        return redirect()->back()->with($notification);
    }
}
