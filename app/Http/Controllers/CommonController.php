<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

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

    public function get_page_menu(){

        $action_name = request()->route()->getName();

        $user_session_data = session()->all();

        $user_right_data = array();
        $menu_data = '';

        if($user_session_data[config('app.app_session_name')]['super_admin_status']==1){

            $user_right_data = DB::table('right_details as a')
                ->join('right_details as b', 'b.cat_id', '=', 'a.cat_id')
                ->select('a.id  as r_id', 'a.cat_id', 'a.r_name', 'a.r_title', 'a.r_action_name', 'a.r_route_name', 'a.r_details', 'a.r_short_order', 'a.r_icon')
                ->where('a.status',1)
                ->where('b.r_route_name',$action_name)
                ->where('a.r_route_name','!=' ,$action_name)
                ->where('a.delete_status',0)
                ->orderBy('a.r_short_order', 'ASC')
                ->get()->toArray();
        }
        else{

        }

        foreach($user_right_data as $data){

            $menu_data .='<button onclick="get_new_page(\''.route($data->r_route_name).'\',\''.$data->r_route_name.'\',\'\',\'\');" type="button" class="btn btn-primary" style="margin-right:10px;"><i class="fa '.$data->r_icon.'"></i>&nbsp;'.$data->r_name.'</button>';
        }

        return $menu_data;
    }

    public function get_duplicate_value($field_name,$table_name, $value, $data_id){

        $field_name = trim($field_name);
        $table_name = trim($table_name);
        $value = trim($value);
        $data_id = trim($data_id);

        $user_right_data = DB::table($table_name)
                ->select('id')
                ->where('delete_status',0)
                ->where($field_name,$value);

        if($data_id!=0 && $data_id!='' && $data_id>0){
                
            $user_right_data->where('id','!=',$data_id);
        }

        $data = $user_right_data->count();

        return $data;
    }
}
