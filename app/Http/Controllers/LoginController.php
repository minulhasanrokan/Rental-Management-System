<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SystemDetails;
use Illuminate\Support\Facades\Hash;
use DB;

class LoginController extends Controller
{
    
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function login_page(){

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

        return view('admin.login_page',compact('system_data'));
    }

    public function login_store(Request $request){

        $request->session()->flush();

        $request->validate(
            [
                'email'    => 'required|email',
                'password' => 'required'
            ]
        );

        $user_data = User::where('email',trim($request->email))
            ->where('delete_status',0)
            ->first();
        
        if(!empty($user_data)){

            $pass_check_status = Hash::check(trim($request->password), $user_data->password);

            if($pass_check_status!=1){

                $notification = array(
                    'message'=> "User E-mail Or Password Does Not Matched!",
                    'alert_type'=>'warning'
                );

                return redirect()->back()->with($notification);
            }
            else{

                if($user_data->status==0){

                    $notification = array(
                        'message'=> "Your Account Was Deactivated, Please Contact With Adminstrator",
                        'alert_type'=>'warning'
                    );

                    return redirect()->back()->with($notification);
                }
                else if($user_data->verify_status==0){

                    $encrypt_data = $this->common->encrypt_data($user_data->id);

                    $notification = array(
                        'message'=> "Please Verify Your Account First",
                        'alert_type'=>'success',
                        'create_status'=>1,
                        'user_id' =>$encrypt_data,
                    );

                    return redirect('/registration')->with($notification);
                }
                else{

                    $user_session_data = (array) json_decode(json_encode($user_data));

                    $request->session()->put($this->app_session_name, $user_session_data);

                    $notification = array(
                        'message'=> "User Account Login Successfully!",
                        'alert_type'=>'success'
                    );

                    return redirect('/dashboard')->with($notification);

                }
            }
        }
        else{

            $notification = array(
                'message'=> "User E-mail Or Password Does Not Matched!",
                'alert_type'=>'warning'
            );

            return redirect()->back()->with($notification);
        }
    }
}
