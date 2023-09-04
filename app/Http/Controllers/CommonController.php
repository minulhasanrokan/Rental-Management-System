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
                //->where('a.r_route_name','!=' ,$action_name)
                ->where('a.delete_status',0)
                ->orderBy('a.r_short_order', 'ASC')
                ->get()->toArray();
        }
        else{

        }

        foreach($user_right_data as $data){

            $menu_data .='<button onclick="get_new_page(\''.route($data->r_route_name).'\',\''.$data->r_title.'\',\'\',\'\');" type="button" class="btn btn-primary" style="margin-right:10px;"><i class="fa '.$data->r_icon.'"></i>&nbsp;'.$data->r_name.'</button>';
        }

        return $menu_data;
    }

    public function get_page_menu_grid($route_name){

        $action_name = request()->route()->getName();

        $user_session_data = session()->all();

        $user_right_data = array();
        $user_right = array();

        if($user_session_data[config('app.app_session_name')]['super_admin_status']==1){

            $user_right_data = DB::table('right_details as a')
                ->join('right_details as b', 'b.cat_id', '=', 'a.cat_id')
                ->select('a.id  as r_id', 'a.cat_id', 'a.r_name', 'a.r_title', 'a.r_action_name', 'a.r_route_name', 'a.r_details', 'a.r_short_order', 'a.r_icon')
                ->where('a.status',1)
                ->where('b.r_route_name',$action_name)
                ->where('a.r_route_name','!=' ,$route_name)
                ->where('a.delete_status',0)
                ->orderBy('a.r_short_order', 'ASC')
                ->get();
        }
        else{

        }

        $sl = 0;

        foreach($user_right_data as $right){

            $user_right[$sl]['r_id'] = $right->r_id;
            $user_right[$sl]['cat_id'] = $right->cat_id;
            $user_right[$sl]['r_name'] = $right->r_name;
            $user_right[$sl]['r_title'] = $right->r_title;
            $user_right[$sl]['r_action_name'] = $right->r_action_name;
            $user_right[$sl]['r_route_name'] = route($right->r_route_name);
            $user_right[$sl]['r_details'] = $right->r_details;
            $user_right[$sl]['r_short_order'] = $right->r_short_order;
            $user_right[$sl]['r_icon'] = $right->r_icon;

            $sl++;
        }

        return $user_right;
    }

    public function get_page_menu_single_view($route_name){

        $route_name_arr = explode("****",$route_name);

        $action_name = request()->route()->getName();

        $user_session_data = session()->all();

        $user_right_data = array();

        if($user_session_data[config('app.app_session_name')]['super_admin_status']==1){

            $user_right_data = DB::table('right_details as a')
                ->join('right_details as b', 'b.cat_id', '=', 'a.cat_id')
                ->select('a.id  as r_id', 'a.cat_id', 'a.r_name', 'a.r_title', 'a.r_action_name', 'a.r_route_name', 'a.r_details', 'a.r_short_order', 'a.r_icon')
                ->where('a.status',1)
                ->where('b.r_route_name',$action_name)
                ->whereNotIn('a.r_route_name',$route_name_arr)
                ->where('a.delete_status',0)
                ->orderBy('a.r_short_order', 'ASC')
                ->get()->toArray();
        }
        else{

        }

        return $user_right_data;
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

    public function get_all_right(){

        $user_right_data = DB::table('right_details')
            ->join('right_categories', 'right_categories.id', '=', 'right_details.cat_id')
            ->join('right_groups', 'right_groups.id', '=', 'right_categories.group_id')
            ->select('right_groups.id as g_id', 'right_groups.name as g_name', 'right_groups.action_name as g_action_name', 'right_groups.details as g_details', 'right_groups.title as g_title', 'right_groups.short_order as g_short_order', 'right_groups.icon as g_icon', 'right_categories.id as c_id', 'right_categories.group_id as group_id', 'right_categories.c_name', 'right_categories.c_title', 'right_categories.c_action_name', 'right_categories.c_details', 'right_categories.short_order as c_short_order', 'right_categories.c_icon as c_icon', 'right_details.id  as r_id', 'right_details.cat_id', 'right_details.r_name', 'right_details.r_title', 'right_details.r_action_name', 'right_details.r_route_name', 'right_details.r_details', 'right_details.r_short_order', 'right_details.r_icon')
            ->where('right_groups.status',1)
            ->where('right_categories.status',1)
            ->where('right_details.status',1)
            ->where('right_groups.delete_status',0)
            ->where('right_categories.delete_status',0)
            ->where('right_details.delete_status',0)
            ->orderBy('right_groups.short_order', 'ASC')
            ->orderBy('right_categories.short_order', 'ASC')
            ->orderBy('right_details.r_short_order', 'ASC')
            ->get()->toArray();

        $right_group_arr = array();
        $right_cat_arr = array();
        $right_arr = array();

        $return_arr = array();

        if(!empty($user_right_data)){

            foreach($user_right_data as $data){

                $right_group_arr[$data->g_id]['g_id'] = $data->g_id;
                $right_group_arr[$data->g_id]['g_name'] = $data->g_name;
                $right_group_arr[$data->g_id]['g_action_name'] = $data->g_action_name;
                $right_group_arr[$data->g_id]['g_details'] = $data->g_details;
                $right_group_arr[$data->g_id]['g_title'] = $data->g_title;
                $right_group_arr[$data->g_id]['g_short_order'] = $data->g_short_order;
                $right_group_arr[$data->g_id]['g_icon'] = $data->g_icon;

                $right_cat_arr[$data->g_id][$data->c_id]['c_id'] = $data->c_id;
                $right_cat_arr[$data->g_id][$data->c_id]['c_name'] = $data->c_name;
                $right_cat_arr[$data->g_id][$data->c_id]['c_title'] = $data->c_title;
                $right_cat_arr[$data->g_id][$data->c_id]['c_action_name'] = $data->c_action_name;
                $right_cat_arr[$data->g_id][$data->c_id]['c_details'] = $data->c_details;
                $right_cat_arr[$data->g_id][$data->c_id]['c_short_order'] = $data->c_short_order;
                $right_cat_arr[$data->g_id][$data->c_id]['c_icon'] = $data->c_icon;

                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_id'] = $data->r_id;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_name'] = $data->r_name;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_title'] = $data->r_title;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_action_name'] = $data->r_action_name;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_route_name'] = $data->r_route_name;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_details'] = $data->r_details;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_short_order'] = $data->r_short_order;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_icon'] = $data->r_icon;
            }
        }

        $return_arr['right_group_arr'] = $right_group_arr;
        $return_arr['right_cat_arr'] = $right_cat_arr;
        $return_arr['right_arr'] = $right_arr;

        return $return_arr;
    }
}
