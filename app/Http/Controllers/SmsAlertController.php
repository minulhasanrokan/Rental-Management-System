<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SMSAlert;
use App\Models\SmsHistory;
use DB;

class SmsAlertController extends Controller
{
    public $common;

    public $app_session_name ='';

    public $header_status = 0;

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');

        $this->header_status = $this->common->check_header_info();
    }

    public function alert_add_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.alert.alert_add',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.alert.alert_add_master',compact('menu_data','system_data'));
        }
    }

    public function alert_history_page($encrypt_id=null){

        $encrypt_id_arr = explode('****', $encrypt_id);

        if(count($encrypt_id_arr)==2)
        {
            $id = $this->common->decrypt_data($encrypt_id_arr[0]);

            $alert_history_data = SmsHistory::where('id',$id)
                ->first();
        
            $menu_data = $this->common->get_page_menu();

            $user_right_data = $this->common->get_page_menu_single_view('sms_email_alert.manage.add****sms_email_alert.manage.view****sms_email_alert.manage.re_send****sms_email_alert.manage.history');

            $header_status = $this->header_status;

            if(empty($alert_history_data)){

                if($header_status==1){

                    return view('admin.404',compact('menu_data','user_right_data'));
                }
                else{

                    $system_data = $this->common->get_system_data();

                    return view('admin.404_master',compact('menu_data','user_right_data','system_data'));
                }
            }
            else{

                $encrypt_id = $encrypt_id_arr[0];

                if($header_status==1){

                    return view('admin.alert.alert_history_single_view',compact('menu_data','alert_history_data','user_right_data','encrypt_id'));
                }
                else{

                    $system_data = $this->common->get_system_data();

                    return view('admin.alert.alert_single_history_view_master',compact('menu_data','alert_history_data','user_right_data','encrypt_id','system_data'));
                }
            }
        }
        else{

            $id = $this->common->decrypt_data($encrypt_id);

            $alert_data = SMSAlert::where('delete_status',0)
                ->where('id',$id)
                ->first();

            $menu_data = $this->common->get_page_menu();

            $header_status = $this->header_status;

            if($header_status==1){

                return view('admin.alert.alert_history_page',compact('menu_data','alert_data'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.alert.alert_history_page_master',compact('menu_data','system_data','alert_data'));
            }
        }
    }

    public function alert_store (Request $request){

        $validator = Validator::make($request->all(), [
            'alert_title' => 'required|string|max:250',
            'alert_details' => 'required'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $errorArray = [];

            foreach ($errors->messages() as $field => $messages) {
                $errorArray[$field] = $messages[0];
            }

            return response()->json([
                'errors' => $errorArray,
                'success' => false,
                'csrf_token' => csrf_token(),
            ]);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new SMSAlert;

        $data->alert_title = $request->alert_title;
        $data->alert_group = $request->alert_group;
        $data->user_id = $request->user_id;
        $data->email_status = $request->email_status;
        $data->alert_details = $request->alert_details;
        $data->add_by = $user_id;
        $data->total_sent = 1;
        $data->created_at = now();
        $data->alert_date = now();

        $data->save();

        if($data==true){

            $status = $this->common->add_user_activity_history('s_m_s_alerts',$data->id,'Add SMS Alert Details');

            if($status==1){

                DB::commit();

                $notification = array(
                    'message'=> "SMS Alert Details Created Successfully",
                    'alert_type'=>'success',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Something Went Wrong Try Again",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "SMS Alert Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function alert_view_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.alert.alert_view',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.alert.alert_view_master',compact('menu_data','system_data'));
        }
    }

    public function alert_re_send_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.alert.alert_re_send',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.alert.alert_re_send_master',compact('menu_data','system_data'));
        }
    }

    public function alert_single_re_send_page($encrypt_id){

        $id = $this->common->decrypt_data($encrypt_id);

        $alert_data = SMSAlert::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('sms_email_alert.manage.add****sms_email_alert.manage.re_send');

        $header_status = $this->header_status;

        if(empty($alert_data)){

            if($header_status==1){

                return view('admin.404',compact('menu_data','user_right_data'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.404_master',compact('menu_data','user_right_data','system_data'));
            }
        }
        else{

            if($header_status==1){

                return view('admin.alert.alert_edit_view',compact('menu_data','alert_data','user_right_data','encrypt_id'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.alert.alert_edit_view_master',compact('menu_data','alert_data','user_right_data','encrypt_id','system_data'));
            }
        }
    }

    public function alert_history_grid ($id=null, Request $request){

        $menu_data = $this->common->get_page_menu_grid('sms_email_alert.manage.add****sms_email_alert.manage.view****sms_email_alert.manage.re_send');

        $user_config_data = $this->common->get_user_config_data();

        $csrf_token = csrf_token();

        $draw = $request->draw;
        $row = $request->start;
        $row_per_page  = $request->length;

        $column_index  = $request->order[0]['column'];
        $column_name = $request->columns[$column_index]['data'];

        $column_ort_order = $request->order[0]['dir'];

        $search_value  = trim($request->search['value']);

        $total_data = array();
        $filter_data = array();
        $data = array();
        $record_data = array();
        $response = array();

        if($id!=null && $id!=0 && $id!=''){

            if($search_value!=''){

                $total_data = DB::table('sms_histories as a')
                    ->join('users as b', 'b.id', '=', 'a.user_id')
                    ->select('a.id')
                    ->where('a.alert_id',$id)
                    ->get();

                $filter_data = DB::table('sms_histories as a')
                    ->join('users as b', 'b.id', '=', 'a.user_id')
                    ->select('a.id')
                    ->where('a.alert_id',$id)
                    ->where(function ($query) use ($search_value) {
                        $query->where('a.alert_title','like',"%".$search_value."%")
                            ->orWhere('b.name','like',"%".$search_value."%");
                    })
                    ->get();

                $data = DB::table('sms_histories as a')
                    ->join('users as b', 'b.id', '=', 'a.user_id')
                    ->select('a.*','b.name as user_name')
                    ->where('a.alert_id',$id)
                    ->where(function ($query) use ($search_value) {
                        $query->where('a.alert_title','like',"%".$search_value."%")
                            ->orWhere('b.name','like',"%".$search_value."%");
                    })
                    ->orderBy($column_name,$column_ort_order)
                    ->offset($row)
                    ->limit($row_per_page)
                    ->get();
            }
            else{

                $filter_data = DB::table('sms_histories as a')
                    ->join('users as b', 'b.id', '=', 'a.user_id')
                    ->select('a.id')
                    ->where('a.alert_id',$id)
                    ->get();

                $data = DB::table('sms_histories as a')
                    ->join('users as b', 'b.id', '=', 'a.user_id')
                    ->select('a.*','b.name as user_name')
                    ->where('a.alert_id',$id)
                    ->orderBy($column_name,$column_ort_order)
                    ->offset($row)
                    ->limit($row_per_page)
                    ->get();

                $total_data = $filter_data;
            }
        }
        else{

            if($search_value!=''){

                $total_data = DB::table('sms_histories as a')
                    ->join('users as b', 'b.id', '=', 'a.user_id')
                    ->select('a.id')
                    ->get();

                $filter_data = DB::table('sms_histories as a')
                    ->join('users as b', 'b.id', '=', 'a.user_id')
                    ->select('a.id')
                    ->where(function ($query) use ($search_value) {
                        $query->where('a.alert_title','like',"%".$search_value."%")
                            ->orWhere('b.name','like',"%".$search_value."%");
                    })
                    ->get();

                $data = DB::table('sms_histories as a')
                    ->join('users as b', 'b.id', '=', 'a.user_id')
                    ->select('a.*','b.name as user_name')
                    ->where(function ($query) use ($search_value) {
                        $query->where('a.alert_title','like',"%".$search_value."%")
                            ->orWhere('b.name','like',"%".$search_value."%");
                    })
                    ->orderBy($column_name,$column_ort_order)
                    ->offset($row)
                    ->limit($row_per_page)
                    ->get();
            }
            else{

                $filter_data = DB::table('sms_histories as a')
                    ->join('users as b', 'b.id', '=', 'a.user_id')
                    ->select('a.id')
                    ->get();

                $data = DB::table('sms_histories as a')
                    ->join('users as b', 'b.id', '=', 'a.user_id')
                    ->select('a.*','b.name as user_name')
                    ->orderBy($column_name,$column_ort_order)
                    ->offset($row)
                    ->limit($row_per_page)
                    ->get();

                $total_data = $filter_data;
            }
        }

        $sl = 0;

        $sl_start = $row+1;

        foreach($data as $value){

            $record_data[$sl]['alert_title'] = $value->alert_title;
            $record_data[$sl]['sl'] = $sl_start;
            $record_data[$sl]['user_name'] = $value->user_name;
            $record_data[$sl]['status'] = $value->status;
            $record_data[$sl]['action'] = $this->common->encrypt_data($value->id).'****01';
            $record_data[$sl]['id'] = $value->id;
            $record_data[$sl]['menu_data'] = $menu_data;

            $sl++;
            $sl_start++;
        }

        $total_records = count($total_data);
        $total_records_with_filer = count($filter_data);

        $response['draw'] = $draw;
        $response['iTotalRecords'] = $total_records;
        $response['iTotalDisplayRecords'] = $total_records_with_filer;
        $response['aaData'] = $record_data;
        $response['csrf_token'] = $csrf_token;

        echo json_encode($response);
    }

    public function alert_grid (Request $request){

        $menu_data = $this->common->get_page_menu_grid('sms_email_alert.manage.add');

        $user_config_data = $this->common->get_user_config_data();

        $csrf_token = csrf_token();

        $draw = $request->draw;
        $row = $request->start;
        $row_per_page  = $request->length;

        $column_index  = $request->order[0]['column'];
        $column_name = $request->columns[$column_index]['data'];

        $column_ort_order = $request->order[0]['dir'];

        $search_value  = trim($request->search['value']);

        $total_data = array();
        $filter_data = array();
        $data = array();
        $record_data = array();
        $response = array();

        if($search_value!=''){

            $total_data = DB::table('s_m_s_alerts as a')
                ->leftjoin('user_groups as b', 'b.id', '=', 'a.alert_group')
                ->leftjoin('users as c', 'c.id', '=', 'a.user_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->get();

            $filter_data = DB::table('s_m_s_alerts as a')
                ->leftjoin('user_groups as b', 'b.id', '=', 'a.alert_group')
                ->leftjoin('users as c', 'c.id', '=', 'a.user_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('a.alert_title','like',"%".$search_value."%")
                        ->orWhere('a.alert_date','like',"%".$search_value."%")
                        ->orWhere('b.group_name','like',"%".$search_value."%")
                        ->orWhere('c.name','like',"%".$search_value."%");
                })
                ->get();

            $data = DB::table('s_m_s_alerts as a')
                ->leftjoin('user_groups as b', 'b.id', '=', 'a.alert_group')
                ->leftjoin('users as c', 'c.id', '=', 'a.user_id')
                ->select('a.*','b.group_name','c.name as user_name')
                ->where('a.delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('a.alert_title','like',"%".$search_value."%")
                        ->orWhere('a.alert_date','like',"%".$search_value."%")
                        ->orWhere('b.group_name','like',"%".$search_value."%")
                        ->orWhere('c.name','like',"%".$search_value."%");
                })
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = DB::table('s_m_s_alerts as a')
                ->leftjoin('user_groups as b', 'b.id', '=', 'a.alert_group')
                ->leftjoin('users as c', 'c.id', '=', 'a.user_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->get();

            $data = DB::table('s_m_s_alerts as a')
                ->leftjoin('user_groups as b', 'b.id', '=', 'a.alert_group')
                ->leftjoin('users as c', 'c.id', '=', 'a.user_id')
                ->select('a.*','b.group_name','c.name as user_name')
                ->where('a.delete_status',0)
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();

            $total_data = $filter_data;
        }

        $sl = 0;

        $sl_start = $row+1;

        foreach($data as $value){

            $record_data[$sl]['alert_title'] = $value->alert_title;
            $record_data[$sl]['sl'] = $sl_start;
            $record_data[$sl]['total_sent'] = $value->total_sent;
            $record_data[$sl]['alert_status'] = $value->alert_status;
            $record_data[$sl]['group_name'] = $value->group_name;
            $record_data[$sl]['user_name'] = $value->user_name;
            $record_data[$sl]['status'] = $value->status;
            $record_data[$sl]['action'] = $this->common->encrypt_data($value->id);
            $record_data[$sl]['id'] = $value->id;
            $record_data[$sl]['menu_data'] = $menu_data;

            $sl++;
            $sl_start++;
        }

        $total_records = count($total_data);
        $total_records_with_filer = count($filter_data);

        $response['draw'] = $draw;
        $response['iTotalRecords'] = $total_records;
        $response['iTotalDisplayRecords'] = $total_records_with_filer;
        $response['aaData'] = $record_data;
        $response['csrf_token'] = $csrf_token;

        echo json_encode($response);
    }

    public function alert_update ($update_id, Request $request){

        $validator = Validator::make($request->all(), [
            'alert_title' => 'required|string|max:250',
            'alert_details' => 'required'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $errorArray = [];

            foreach ($errors->messages() as $field => $messages) {
                $errorArray[$field] = $messages[0];
            }

            return response()->json([
                'errors' => $errorArray,
                'success' => false,
                'csrf_token' => csrf_token(),
            ]);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = SMSAlert::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        if($data->send_status==0)
        {
            $notification = array(
                'message'=> "Alert Details Pending In send Queue",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            $data->edit_status = 1;
            $data->send_status = 0;
            $data->alert_status = 0;
            $data->email_send_status = 0;
            $data->total_sent = $data->total_sent+1;
            $data->edit_by = $user_id;
            $data->updated_at = now();

            $data->save();

            if($data==true){

                $status = $this->common->add_user_activity_history('s_m_s_alerts',$data->id,'Edit SMS Alert Details');

                if($status==1){

                    DB::commit();

                    $notification = array(
                        'message'=> "Alert Details Updated Successfully",
                        'alert_type'=>'success',
                        'csrf_token' => csrf_token()
                    );
                }
                else{

                    DB::rollBack();

                    $notification = array(
                        'message'=> "Something Went Wrong Try Again",
                        'alert_type'=>'warning',
                        'csrf_token' => csrf_token()
                    );
                }
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Alert Details Updated Successfully",
                    'alert_type'=>'success',
                    'csrf_token' => csrf_token()
                );
            }
        }

        return response()->json($notification);
    }

    public function alert_single_view_page($encrypt_id){

        $id = $this->common->decrypt_data($encrypt_id);

        $alert_data = SMSAlert::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('sms_email_alert.manage.add****sms_email_alert.manage.view');

        $header_status = $this->header_status;

        if(empty($alert_data)){

            if($header_status==1){

                return view('admin.404',compact('menu_data','user_right_data'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.404_master',compact('menu_data','user_right_data','system_data'));
            }
        }
        else{

            if($header_status==1){

                return view('admin.alert.alert_single_view',compact('menu_data','alert_data','user_right_data','encrypt_id'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.alert.alert_single_view_master',compact('menu_data','alert_data','user_right_data','encrypt_id','system_data'));
            }
        }
    }
}