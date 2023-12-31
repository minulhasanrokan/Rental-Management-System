<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserConfig;
use App\Models\SystemDetails;
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

    public function current_tenant_info_by_unit($value){

        $rent_data = DB::table('rents as a')
            ->join('users as b', 'b.id', '=', 'a.tenant_id')
            ->select('a.*', 'b.name as tenant_name')
            ->where('a.delete_status',0)
            ->where('a.status',1)
            ->where('b.delete_status',0)
            ->where('b.status',1)
            ->where('a.unit_id',$value)
            ->get()->toArray();

        return json_encode($rent_data);
    }

    function check_session(){

        $user_session_data = session()->all();

        if(isset($user_session_data[config('app.app_session_name')])){

        }
    }

    public function check_header_info(){

        $customHeader = 'X_DATA_TOKEN';

        if (isset($_SERVER['HTTP_'.$customHeader])){
            
            return 1;
        }
        else{
            
            return 0;
        }
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

            $user_id = $user_session_data[config('app.app_session_name')]['id'];

            $user_right_data = DB::table('right_details as a')
                ->join('right_details as b', 'b.cat_id', '=', 'a.cat_id')
                ->join('user_rights as c', 'c.r_id', '=', 'a.id')
                ->select('a.id  as r_id', 'a.cat_id', 'a.r_name', 'a.r_title', 'a.r_action_name', 'a.r_route_name', 'a.r_details', 'a.r_short_order', 'a.r_icon')
                ->where('a.status',1)
                ->where('c.user_id',$user_id)
                ->where('b.r_route_name',$action_name)
                //->where('a.r_route_name','!=' ,$action_name)
                ->where('a.delete_status',0)
                ->orderBy('a.r_short_order', 'ASC')
                ->get()->toArray();
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

        $route_name_arr = explode("****",$route_name);

        if($user_session_data[config('app.app_session_name')]['super_admin_status']==1){

            $user_right_data = DB::table('right_details as a')
                ->join('right_details as b', 'b.cat_id', '=', 'a.cat_id')
                ->select('a.id  as r_id', 'a.cat_id', 'a.r_name', 'a.r_title', 'a.r_action_name', 'a.r_route_name', 'a.r_details', 'a.r_short_order', 'a.r_icon')
                ->where('a.status',1)
                ->where('b.r_route_name',$action_name)
                ->whereNotIn('a.r_route_name',$route_name_arr)
                ->where('a.delete_status',0)
                ->orderBy('a.r_short_order', 'ASC')
                ->get();
        }
        else{

            $user_id = $user_session_data[config('app.app_session_name')]['id'];

            $user_right_data = DB::table('right_details as a')
                ->join('right_details as b', 'b.cat_id', '=', 'a.cat_id')
                ->join('user_rights as c', 'c.r_id', '=', 'a.id')
                ->select('a.id  as r_id', 'a.cat_id', 'a.r_name', 'a.r_title', 'a.r_action_name', 'a.r_route_name', 'a.r_details', 'a.r_short_order', 'a.r_icon')
                ->where('a.status',1)
                ->where('c.user_id',$user_id)
                ->where('b.r_route_name',$action_name)
                ->whereNotIn('a.r_route_name',$route_name_arr)
                ->where('a.delete_status',0)
                ->orderBy('a.r_short_order', 'ASC')
                ->get();
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

    public function get_single_route_details(){

        $action_name = request()->route()->getName();

        $user_session_data = session()->all();

        $user_right_data = array();

        if($user_session_data[config('app.app_session_name')]['super_admin_status']==1){

            $user_right_data = DB::table('right_details as a')
                ->join('right_categories as b', 'b.id', '=', 'a.cat_id')
                ->join('right_groups as d', 'd.id', '=', 'b.group_id')
                ->select('a.*','d.id as group_id')
                ->where('a.status',1)
                ->where('a.r_route_name',$action_name)
                ->where('a.delete_status',0)
                ->first();
        }
        else{

            $user_id = $user_session_data[config('app.app_session_name')]['id'];

            $user_right_data = DB::table('right_details as a')
                ->join('user_rights as c', 'c.r_id', '=', 'a.id')
                ->join('right_categories as b', 'b.id', '=', 'a.cat_id')
                ->join('right_groups as d', 'd.id', '=', 'b.group_id')
                ->select('a.*','d.id as group_id')
                ->where('a.status',1)
                ->where('c.user_id',$user_id)
                ->where('a.r_route_name',$action_name)
                ->where('a.delete_status',0)
                ->orderBy('a.r_short_order', 'ASC')
                ->first();
        }

        return $user_right_data;
    }

    public function add_user_activity_history($table_name=null,$table_id=null,$activity=null,$search_data=null){

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        $action_data = $this->get_single_route_details();

        $group_id = isset($action_data->group_id)?$action_data->group_id:'';
        $cat_id = isset($action_data->cat_id)?$action_data->cat_id:'';
        $right_id = isset($action_data->id)?$action_data->id:'';

        $ip_address = request()->ip();
        $mac_address = '';

        $data_arr = array(
            'group_id' => $group_id,
            'category_id' => $cat_id,
            'right_id' => $right_id,
            'table_name' =>$table_name,
            'table_id' =>$table_id,
            'activity' =>$activity,
            'ip_address' =>$ip_address,
            'mac_address' =>$mac_address,
            'search_data' =>$search_data,
            'add_by' => $user_id,
            'created_at' =>now()
        );

        try {

            DB::table('activity_histories')->insert($data_arr);
            
            return 1;
        } catch (\Exception $e) {
            
            return 0;
        }
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

            $user_id = $user_session_data[config('app.app_session_name')]['id'];

            $user_right_data = DB::table('right_details as a')
                ->join('right_details as b', 'b.cat_id', '=', 'a.cat_id')
                ->join('user_rights as c', 'c.r_id', '=', 'a.id')
                ->select('a.id  as r_id', 'a.cat_id', 'a.r_name', 'a.r_title', 'a.r_action_name', 'a.r_route_name', 'a.r_details', 'a.r_short_order', 'a.r_icon')
                ->where('a.status',1)
                ->where('c.user_id',$user_id)
                ->where('b.r_route_name',$action_name)
                ->whereNotIn('a.r_route_name',$route_name_arr)
                ->where('a.delete_status',0)
                ->orderBy('a.r_short_order', 'ASC')
                ->get()->toArray();
        }

        return $user_right_data;
    }

    public function get_duplicate_value($field_name,$table_name, $value, $data_id){

        $field_name = trim($field_name);
        $table_name = trim($table_name);
        $value = urldecode(trim($value));
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

    public function get_duplicate_value_two($field_name, $field_name2, $table_name, $value, $value2, $data_id,$other_status='',$other_value=''){

        $field_name = trim($field_name);
        $field_name2 = trim($field_name2);
        $table_name = trim($table_name);
        $value = urldecode(trim($value));
        $value2 = urldecode(trim($value2));
        $data_id = trim($data_id);

        $other_status_arr = array();
        $other_value_arr = array();

        if($other_status!='' && $other_status!=0)
        {
            $other_status_arr = explode("****",$other_status);
            $other_value_arr = explode("****",$other_value);
        }

        $where_other_arr = array();

        foreach($other_status_arr as $key=>$other_data)
        {
            $where_other_arr[$other_data] = $other_value_arr[$key];
        }

        $field_name2_arr = explode(",",$field_name2);
        $value2_arr = explode(",",$value2);

        $where_arr = array();

        foreach($field_name2_arr as $key=>$data){

            $where_arr[$data] = $value2_arr[$key];
        }

        $user_right_data = DB::table($table_name)
            ->select('id')
            ->where('delete_status',0)
            ->where($field_name,$value)
            ->where($where_arr)
            ->where($where_other_arr);

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

    public function get_group_right($group_id){

        $group_right_data = DB::table('user_group_rights')
            ->select('group_id','g_id','c_id','r_id','add_by','edit_by')
            //->where('status',1)
            ->where('delete_status',0)
            ->where('group_id',$group_id)
            ->get()
            ->toArray();

        $group_right_data_arr = array();

        if(!empty($group_right_data)){

            foreach($group_right_data as $data){

                $group_right_data_arr[$data->g_id][$data->c_id][$data->r_id]['r_id'] = $data->r_id;
                $group_right_data_arr[$data->g_id][$data->c_id][$data->r_id]['add_by'] = $data->add_by;
                $group_right_data_arr[$data->g_id][$data->c_id][$data->r_id]['edit_by'] = $data->edit_by;
                $group_right_data_arr[$data->g_id][$data->c_id][$data->r_id]['group_id'] = $data->group_id;
            }
        }

        return $group_right_data_arr;
    }

    public function get_user_right($user_id){

        $group_right_data = DB::table('user_rights')
            ->select('user_id','g_id','c_id','r_id','add_by','edit_by')
            //->where('status',1)
            ->where('delete_status',0)
            ->where('user_id',$user_id)
            ->get()
            ->toArray();

        $group_right_data_arr = array();

        if(!empty($group_right_data)){

            foreach($group_right_data as $data){

                $group_right_data_arr[$data->g_id][$data->c_id][$data->r_id]['r_id'] = $data->r_id;
                $group_right_data_arr[$data->g_id][$data->c_id][$data->r_id]['add_by'] = $data->add_by;
                $group_right_data_arr[$data->g_id][$data->c_id][$data->r_id]['edit_by'] = $data->edit_by;
                $group_right_data_arr[$data->g_id][$data->c_id][$data->r_id]['user_id'] = $data->user_id;
            }
        }

        return $group_right_data_arr;
    }

    public function get_data_by_id($table_name,$data_id,$field_name){

        $table_name = trim($table_name);
        $field_name = trim($field_name);
        $data_id = trim($data_id);

        if($field_name=='' || $field_name==null || $field_name==0){

            $select = "*";
        }
        else{

            $field_name_arr = explode(",",$field_name);

            $sl =0;

            foreach($field_name_arr as $data){

                $select[$sl] = $data;

                $sl++;
            }
        }

        $data_id_arr = explode(",",$data_id);

        $parameter_data = DB::table($table_name)
            ->select($select)
            ->where('delete_status',0)
            ->whereIn('id',$data_id_arr)
            ->get()
            ->toArray();

        return $parameter_data;
    }

    public function get_parameter_data($table_name,$field_name,$value){

        $table_name = trim($table_name);
        $field_name = trim($field_name);

        if($field_name=='' || $field_name==null || $field_name==0){

            $select = "*";
        }
        else{

            $field_name_arr = explode(",",$field_name);

            $sl =0;

            foreach($field_name_arr as $data){

                $select[$sl] = $data;

                $sl++;
            }
        }

        $parameter_data = array();

        if($value!='' && $value!=0){

            $where_arr = array(
                'delete_status' =>0,
                'status' => 1
            );

            $parameter_data = DB::table($table_name)
                ->select($select)
                ->where($where_arr)
                ->orWhere('id',$value)
                ->get()
                ->toArray();
        }
        else{

            $parameter_data = DB::table($table_name)
                ->select($select)
                ->where('delete_status',0)
                ->where('status',1)
                ->get()
                ->toArray();
        }

        return $parameter_data;
    }

    public function get_parameter_data_by_id($table_name,$field_name,$value,$data_value,$data_name,$other_status,$other_value){

        $table_name = trim($table_name);
        $field_name = trim($field_name);

        $data_value = trim($data_value);
        $data_name = trim($data_name);

        if($field_name=='' || $field_name==null || $field_name==0){

            $select = "*";
        }
        else{

            $field_name_arr = explode(",",$field_name);

            $sl =0;

            foreach($field_name_arr as $data){

                $select[$sl] = $data;

                $sl++;
            }
        }

        $other_status_arr = array();
        $other_value_arr = array();

        if($other_status!='' && $other_status!=0)
        {
            $other_status_arr = explode("****",$other_status);
            $other_value_arr = explode("****",$other_value);
        }

        $where_other_arr = array();

        foreach($other_status_arr as $key=>$other_data)
        {
            $where_other_arr[$other_data] = $other_value_arr[$key];
        }

        $parameter_data = array();

        if($value!='' && $value!=0){

            $where_arr = array(
                'delete_status' =>0,
                'status' => 1
            );

            $parameter_data = DB::table($table_name)
                ->select($select)
                ->where($where_arr)
                ->where($where_other_arr)
                ->where($data_name,$data_value)
                ->orWhere('id',$value)
                ->get()
                ->toArray();
        }
        else{

            $parameter_data = DB::table($table_name)
                ->select($select)
                ->where($data_name,$data_value)
                ->where($where_other_arr)
                ->where('delete_status',0)
                ->where('status',1)
                ->get()
                ->toArray();
        }

        return $parameter_data;
    }

    public function get_user_config_data(){

        $data = UserConfig::where('delete_status',0)
            ->where('status',1)
            //->where('id',1)
            ->first();

        $user_config_data = array();

        if($data!=''){

            $user_config_data['normal_user_type']=$data->normal_user_type;
            $user_config_data['owner_user_type']=$data->owner_user_type;
            $user_config_data['tenant_user_type']=$data->tenant_user_type;
            $user_config_data['employee_user_type']=$data->employee_user_type;
            $user_config_data['tenant_user_group']=$data->tenant_user_group;
            $user_config_data['normal_user_group']=$data->normal_user_group;
            $user_config_data['owner_user_group']=$data->owner_user_group;
            $user_config_data['employee_user_group']=$data->employee_user_group;
            $user_config_data['add_by']=$data->add_by;
            $user_config_data['edit_by']=$data->edit_by;
            $user_config_data['delete_by']=$data->delete_by;
            $user_config_data['id']=$data->id;
            $user_config_data['edit_status']=$data->edit_status;
            $user_config_data['delete_status']=$data->delete_status;
            $user_config_data['session_time']=$data->session_time;
        }
        else{
            
            $user_config_data['normal_user_type']='';
            $user_config_data['owner_user_type']='';
            $user_config_data['tenant_user_type']='';
            $user_config_data['employee_user_type']='';
            $user_config_data['tenant_user_group']='';
            $user_config_data['normal_user_group']='';
            $user_config_data['owner_user_group']='';
            $user_config_data['employee_user_group']='';
            $user_config_data['add_by']='';
            $user_config_data['edit_by']='';
            $user_config_data['delete_by']='';
            $user_config_data['id']='';
            $user_config_data['edit_status']='';
            $user_config_data['delete_status']='';
            $user_config_data['session_time']='';
        }

        return $user_config_data;
    }

    function update_user_session_time($session_time){

        $updateData = [
            'user_session_time' => $session_time,
        ];

        try {

            DB::table('users')->update($updateData);
            
            return 1;

        } catch (\Exception $e) {
            
            return 0;
        }
    }

    public function get_system_data(){

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

        return $system_data;
    }

    public function send_email_alert(){

        $sms_data = DB::table('s_m_s_alerts  as a')
            ->select('a.*')
            ->where('a.status',1)
            ->where('a.email_status',1)
            ->where('a.send_status',0)
            ->where('a.delete_status',0)
            ->get()->toArray();

        $email_data_arr = array();

        if(!empty($sms_data)){

            $sl = 0;
            $sl1 = 0;

            foreach($sms_data as $data){

                $user_id = $data->user_id==''?'0':$data->user_id;
                $alert_group = $data->alert_group==''?'0':$data->alert_group;

                $user_data = DB::table('users as a')
                    ->select('a.id', 'a.email', 'a.mobile', 'a.group','a.name as user_name')
                    ->where('a.delete_status', 0);

                if ($alert_group != 0) {
                    $user_data->where('a.group', $alert_group);
                }

                if ($user_id != 0) {
                    $user_data->where('a.id', $user_id);
                }

                $user_data_result = $user_data->get()->toArray();

                $user_session_data = session()->all();

                $user_id = $user_session_data[config('app.app_session_name')]['id'];
                
                if(!empty($user_data_result))
                {
                    foreach($user_data_result as $user_data){

                        $email_data_arr[$sl]['user_id'] = $user_data->id;
                        $email_data_arr[$sl]['user_name'] = $user_data->user_name;
                        $email_data_arr[$sl]['email'] = $user_data->email;
                        $email_data_arr[$sl]['mobile'] = $user_data->mobile;
                        $email_data_arr[$sl]['group'] = $user_data->group;
                        $email_data_arr[$sl]['email_status'] = $data->email_status;
                        $email_data_arr[$sl]['alert_title'] = $data->alert_title;
                        $email_data_arr[$sl]['alert_details'] = trim($data->alert_details);
                        $email_data_arr[$sl]['alert_id'] = $data->id;
                        $email_data_arr[$sl]['add_by'] = $user_id;
                        $email_data_arr[$sl]['created_at'] = now();

                        $sl++;
                    }
                }
            }
        }

        if(!empty($email_data_arr)){

            $send_status_arr = array();
            $table_arr = array();

            $sl = 0;

            $mail_data = new MailController();

            foreach($email_data_arr as $data){

                $sms = $data['alert_details'];
                $email = $data['email'];
                $alert_title = $data['alert_title'];
                $user_name = $data['user_name'];

                $send_status = $mail_data->send_email($email,$sms,$alert_title,$user_name);

                if($send_status==true){

                    $send_status_arr[$sl]['send_status'] = 1;
                    $send_status_arr[$sl]['user_id'] = $data['user_id'];
                    $send_status_arr[$sl]['group'] = $data['group'];
                    $send_status_arr[$sl]['mobile'] = $data['mobile'];
                    $send_status_arr[$sl]['email'] = $data['email'];
                    $send_status_arr[$sl]['alert_title'] = $data['alert_title'];
                    $send_status_arr[$sl]['alert_details'] = $data['alert_details'];
                    $send_status_arr[$sl]['add_by'] = $data['add_by'];
                    $send_status_arr[$sl]['alert_id'] = $data['alert_id'];
                    $send_status_arr[$sl]['created_at'] = $data['created_at'];

                    $table_arr[$sl]= $data['alert_id'];

                    $sl++;
                }
            }

            if(!empty($send_status_arr)){

                DB::table('email_histories')->insert($send_status_arr);

                DB::table('s_m_s_alerts')
                    ->whereIn('id', $table_arr)
                    ->update(['email_send_status' => 1]);
            }
        }
    }

    public function send_sms_alert(){

        $sms_data = DB::table('s_m_s_alerts  as a')
            ->select('a.*')
            ->where('a.status',1)
            ->where('a.send_status',0)
            ->where('a.delete_status',0)
            ->get()->toArray();

        $user_data_arr = array();

        if(!empty($sms_data)){

            $sl = 0;
            $sl1 = 0;

            foreach($sms_data as $data){

                $user_id = $data->user_id==''?'0':$data->user_id;
                $alert_group = $data->alert_group==''?'0':$data->alert_group;

                $user_data = DB::table('users as a')
                    ->select('a.id', 'a.email', 'a.mobile', 'a.group')
                    ->where('a.delete_status', 0);

                if ($alert_group != 0) {
                    $user_data->where('a.group', $alert_group);
                }

                if ($user_id != 0) {
                    $user_data->where('a.id', $user_id);
                }

                $user_data_result = $user_data->get()->toArray();

                $user_session_data = session()->all();

                $user_id = $user_session_data[config('app.app_session_name')]['id'];
                
                if(!empty($user_data_result))
                {
                    foreach($user_data_result as $user_data){

                        $user_data_arr[$sl]['user_id'] = $user_data->id;
                        $user_data_arr[$sl]['email'] = $user_data->email;
                        $user_data_arr[$sl]['mobile'] = $user_data->mobile;
                        $user_data_arr[$sl]['group'] = $user_data->group;
                        $user_data_arr[$sl]['email_status'] = $data->email_status;
                        $user_data_arr[$sl]['alert_title'] = $data->alert_title;
                        $user_data_arr[$sl]['alert_details'] = trim($data->alert_details);
                        $user_data_arr[$sl]['alert_id'] = $data->id;
                        $user_data_arr[$sl]['add_by'] = $user_id;
                        $user_data_arr[$sl]['created_at'] = now();

                        $sl++;
                    }
                }
            }
        }

        if(!empty($user_data_arr)){

            $send_status_arr = array();
            $table_arr = array();

            $sl = 0;

            foreach($user_data_arr as $data){

                $sms = $data['alert_details'];
                $mobile = $data['mobile'];

                $send_status = $this->send_sms($mobile,$sms);

                $send_status_arr[$sl]['send_status'] = $send_status;
                $send_status_arr[$sl]['user_id'] = $data['user_id'];
                $send_status_arr[$sl]['group'] = $data['group'];
                $send_status_arr[$sl]['mobile'] = $data['mobile'];
                $send_status_arr[$sl]['email'] = $data['email'];
                $send_status_arr[$sl]['alert_title'] = $data['alert_title'];
                $send_status_arr[$sl]['alert_details'] = $data['alert_details'];
                $send_status_arr[$sl]['add_by'] = $data['add_by'];
                $send_status_arr[$sl]['alert_id'] = $data['alert_id'];
                $send_status_arr[$sl]['created_at'] = $data['created_at'];

                $table_arr[$sl]= $data['alert_id'];

                $sl++;
            }

            if(!empty($send_status_arr)){

                DB::table('sms_histories')->insert($send_status_arr);

                DB::table('s_m_s_alerts')
                    ->whereIn('id', $table_arr)
                    ->update(['send_status' => 1, 'alert_status' => 1]);
            }
        }
    }

    public function send_sms($mobile,$sms){

        $to = $mobile;
        $message = $sms;

        $data = SystemDetails::where('delete_status',0)
            ->where('status',1)
            //->where('id',1)
            ->first();

        $api_data = DB::table('sms_apis as a')
            ->select('a.*')
            ->where('status',1)
            ->first();

        if(!empty($api_data)){

            /*$token = $api_data->api_token;
            $url = $api_data->api_url;

            $data= array(
                'to'=>"$to",
                'message'=>"$message",
                'token'=>"$token"
            );

            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $smsresult = curl_exec($ch);
            */
            return 1;
        }
        else{

            return 0;
        }
    }
}
