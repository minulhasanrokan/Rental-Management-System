<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Mail\DynamicMail;
use App\Mail\VerifyMailWithPassword;
use App\Mail\ResetPasswordMail;

class MailController extends Controller
{

    public function sent_email_verify_email($email,$encrypt_data,$user_name){

        $base_url = config('app.url');

        $url = $base_url."/verify-email/".$encrypt_data;

        $status = Mail::to($email)->send(new VerifyEmail($user_name, $url));

        return $status;
    }

    public function send_email($email,$sms,$title=null,$to_user=null){

        try {

            Mail::to($email)->send(new DynamicMail($email,$sms,$title,$to_user));

            return true;

        } catch (\Exception $e){

            return false;
        }
    }

    public function sent_password_reset_email($email,$encrypt_data,$user_name,$token){

        $base_url = config('app.url');

        $url = $base_url."/reset-password/".$encrypt_data;

        $status = Mail::to($email)->send(new ResetPasswordMail($user_name, $url,$token));

        return $status;
    }

    public function sent_email_verify_email_with_password($email,$encrypt_data,$user_name,$password){

        $base_url = config('app.url');

        $url = $base_url."/verify-email/".$encrypt_data;

        $status = Mail::to($email)->send(new VerifyMailWithPassword($user_name,$password, $url));

        return $status;
    }
}
