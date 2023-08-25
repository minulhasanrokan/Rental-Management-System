<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{

    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function registration_page(){

        return view('admin.registration_page');
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

            $notification = array(
                'message'=> "User Account Created Successfully",
                'alert-type'=>'info',
                'create_status'=>1,
                'user_id' =>$encrypt_data,
            );
        }
        else{

            $notification = array(
                'message'=> "User Account Does Not Created Successfully",
                'alert-type'=>'warning',
                'create_status'=>0,
                'user_id' =>'',
            );
        }

        return redirect()->back()->with($notification);
    }
}
