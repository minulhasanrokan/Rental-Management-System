<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{

    public $app_session_name ='';

    public function __construct(){

        $this->app_session_name = config('app.app_session_name');
    }

    public function encrypt_data($data){

        $encrypt_method = $this->app_session_name = config('app.app_encrypt_method');
        $secret_key = $this->app_session_name = config('app.app_encrypt_secret_key');
        $secret_iv = $this->app_session_name = config('app.app_encrypt_secret_iv');

        // hash
        $key = hash($this->app_session_name = config('app.app_encrypt_hash'), $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash($this->app_session_name = config('app.app_encrypt_hash'), $secret_iv), 0, 16);

        // encrypt token;
        $encryptToken = openssl_encrypt($data, $encrypt_method, $key, 0, $iv);
        $encryptToken = base64_encode($encryptToken);

        return urlencode($encryptToken);
    }

    public function decrypt_data($data){

        $data = urldecode($data);

        $encrypt_method = $this->app_session_name = config('app.app_encrypt_method');
        $secret_key = $this->app_session_name = config('app.app_encrypt_secret_key');
        $secret_iv = $this->app_session_name = config('app.app_encrypt_secret_iv');

        // hash
        $key = hash($this->app_session_name = config('app.app_encrypt_hash'), $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash($this->app_session_name = config('app.app_encrypt_hash'), $secret_iv), 0, 16);

        // decrypt token;
        $decrypt_data = openssl_decrypt(base64_decode($data), $encrypt_method, $key, 0, $iv);

        return $decrypt_data;
    }
}
