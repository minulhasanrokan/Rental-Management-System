<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;

class MailController extends Controller
{

    public function sent_email_verify_email($email,$encrypt_data,$user_name){

        $base_url = config('app.url');

        $url = $base_url."/verify-email/".$encrypt_data;

        $status = Mail::to($email)->send(new VerifyEmail($user_name, $url));

        return $status;
    }
}
