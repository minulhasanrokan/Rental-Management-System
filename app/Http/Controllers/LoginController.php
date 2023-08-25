<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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

        return view('admin.login_page');
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
                    'alert-type'=>'warning'
                );

                return redirect()->back()->with($notification);
            }
            else{

                if($user_data->verify_status==0){

                    $encrypt_data = $this->common->encrypt_data($user_data->id);

                    $notification = array(
                        'message'=> "Please Verify Your Account First",
                        'alert-type'=>'info',
                        'create_status'=>1,
                        'user_id' =>$encrypt_data,
                    );

                    return redirect('/registration')->with($notification);
                }
                else if($user_data->status==0){

                    $notification = array(
                        'message'=> "Your Account Was Deactivated, Please Contact With Adminstrator",
                        'alert-type'=>'warning'
                    );

                    return redirect()->back()->with($notification);
                }
                else{

                    $user_session_data = (array) json_decode(json_encode($user_data));

                    $request->session()->put($this->app_session_name, $user_session_data);

                    $notification = array(
                        'message'=> "User Account Login Successfully!",
                        'alert-type'=>'warning'
                    );

                    return redirect('/dashboard')->with($notification);

                }
            }
        }
        else{

            $notification = array(
                'message'=> "User E-mail Or Password Does Not Matched!",
                'alert-type'=>'warning'
            );

            return redirect()->back()->with($notification);
        }
    }
}
