<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemDetails;
use DB;

class DashboardController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function logout(){

        $this->common->update_user_log_time(1,0);

        session()->flush();
        //session()->regenerate();

        $notification = array(
            'message'=> "User Account Logout Successfully!",
            'alert_type'=>'success'
        );

        return redirect('/login')->with($notification);
    }

    public function dashboard(){

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

        $user_session_data = session()->all();

        $admin_status = $user_session_data[config('app.app_session_name')]['super_admin_status'];

        $user_right_data = array();

        if($admin_status==1)
        {
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
        }
        else{

            $user_id = $user_session_data[config('app.app_session_name')]['id'];

            $user_right_data = DB::table('right_details')
                ->join('user_rights', 'right_details.id', '=', 'user_rights.r_id')
                ->join('right_categories', 'right_categories.id', '=', 'right_details.cat_id')
                ->join('right_groups', 'right_groups.id', '=', 'right_categories.group_id')
                ->select('right_groups.id as g_id', 'right_groups.name as g_name', 'right_groups.action_name as g_action_name', 'right_groups.details as g_details', 'right_groups.title as g_title', 'right_groups.short_order as g_short_order', 'right_groups.icon as g_icon', 'right_categories.id as c_id', 'right_categories.group_id as group_id', 'right_categories.c_name', 'right_categories.c_title', 'right_categories.c_action_name', 'right_categories.c_details', 'right_categories.short_order as c_short_order', 'right_categories.c_icon as c_icon', 'right_details.id  as r_id', 'right_details.cat_id', 'right_details.r_name', 'right_details.r_title', 'right_details.r_action_name', 'right_details.r_route_name', 'right_details.r_details', 'right_details.r_short_order', 'right_details.r_icon')
                ->where('user_rights.user_id',$user_id)
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
        }

        $right_group_arr = array();
        $right_cat_arr = array();
        $right_arr = array();
        $dash_board_right_arr = array();

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

                $dash_board_right_arr['r_route_name'][$data->g_action_name][$data->c_action_name][$data->r_route_name] = $data->r_route_name;
                $dash_board_right_arr['r_title'][$data->g_action_name][$data->c_action_name][$data->r_route_name] = $data->r_title;
                $dash_board_right_arr['r_icon'][$data->g_action_name][$data->c_action_name][$data->r_route_name] = $data->r_icon;
            }
        }

        $common = $this->common;

       return view('admin.dashboard',compact('system_data','dash_board_right_arr','admin_status','common','user_session_data'));
    }
}
