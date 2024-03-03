<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Designation;
use DB;

class DesignationController extends Controller
{
    public $common;

    public $app_session_name ='';

    public $header_status = 0;

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');

        $this->header_status = $this->common->check_header_info();
    }

    public function designation_add_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.designation.designation_add',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.designation.designation_add_master',compact('menu_data','system_data'));
        }
    }

    public function designation_store(Request $request){

        $validator = Validator::make($request->all(), [
            'designation_name' => 'required|string|max:250',
            'designation_code' => 'required|string|max:20',
            'designation_title' => 'required|string|max:250'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('designation_name','designations', $request->designation_name, 0);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Designation Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('designation_code','designations', $request->designation_code, 0);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Designation Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new Designation;

        $data->designation_name = $request->designation_name;
        $data->designation_code = $request->designation_code;
        $data->designation_title = $request->designation_title;
        $data->designation_deatils = $request->designation_deatils;
        $data->add_by = $user_id;
        $data->created_at = now();

        $data->save();

        if($data==true){

            $status = $this->common->add_user_activity_history('designations',$data->id,'Add Designation Details');

            if($status==1){

                DB::commit();

                $notification = array(
                    'message'=> "Designation Details Created Successfully",
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
                'message'=> "Designation Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function designation_edit_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.designation.designation_edit',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.designation.designation_edit_master',compact('menu_data','system_data'));
        }

    }

    public function designation_grid(Request $request){

        $menu_data = $this->common->get_page_menu_grid('reference_data.designation.add');

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

            $total_data = Designation::select('id')
                ->where('delete_status',0)
                ->get();

            $filter_data = Designation::select('id')
                ->where('delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('designation_name','like',"%".$search_value."%")
                        ->orWhere('designation_code','like',"%".$search_value."%")
                        ->orWhere('designation_title','like',"%".$search_value."%");
                })
                ->get();

            $data = Designation::select('id','designation_name','designation_code','designation_title','designation_deatils','add_by','edit_by','status')
                ->where('delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('designation_name','like',"%".$search_value."%")
                        ->orWhere('designation_code','like',"%".$search_value."%")
                        ->orWhere('designation_title','like',"%".$search_value."%");
                })
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = Designation::select('id')
                ->where('delete_status',0)
                ->get();

            $data = Designation::select('id','designation_name','designation_code','designation_title','designation_deatils','add_by','edit_by','status')
                ->where('delete_status',0)
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();

            $total_data = $filter_data;
        }

        $sl = 0;

        $sl_start = $row+1;

        foreach($data as $value){

            $record_data[$sl]['id'] = $value->id;
            $record_data[$sl]['sl'] = $sl_start;
            $record_data[$sl]['designation_name'] = $value->designation_name;
            $record_data[$sl]['designation_code'] = $value->designation_code;
            $record_data[$sl]['designation_title'] = $value->designation_title;
            $record_data[$sl]['designation_deatils'] = $value->designation_deatils;
            $record_data[$sl]['add_by'] = $value->add_by;
            $record_data[$sl]['edit_by'] = $value->edit_by;
            $record_data[$sl]['status'] = $value->status;
            $record_data[$sl]['action'] = $this->common->encrypt_data($value->id);
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
        $response['csrf_token'] = csrf_token();

        echo json_encode($response);
    }

    public function designation_single_edit_page($encrypt_id){

        $id = $this->common->decrypt_data($encrypt_id);

        $designation_data = Designation::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('reference_data.designation.add****reference_data.designation.edit');

        $header_status = $this->header_status;

        if(empty($designation_data)){

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

                return view('admin.reference.designation.designation_edit_view',compact('menu_data','designation_data','user_right_data','encrypt_id'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.reference.designation.designation_edit_view_master',compact('menu_data','designation_data','user_right_data','encrypt_id','system_data'));
            }
        }
    }

    public function designation_update($id, Request $request){

        $validator = Validator::make($request->all(), [
            'designation_name' => 'required|string|max:250',
            'designation_code' => 'required|string|max:20',
            'designation_title' => 'required|string|max:250'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('designation_name','designations', $request->designation_name, $request->update_id);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Designation Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('designation_code','designations', $request->designation_code, $request->update_id);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Designation Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = Designation::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->designation_name = $request->designation_name;
        $data->designation_code = $request->designation_code;
        $data->designation_title = $request->designation_title;
        $data->designation_deatils = $request->designation_deatils;
        $data->edit_by = $user_id;
        $data->updated_at = now();
        $data->edit_status = 1;

        $data->save();

        if($data==true){

            $status = $this->common->add_user_activity_history('designations',$data->id,'Edit Designation Details');

            if($status==1){

                DB::commit();

                $notification = array(
                    'message'=> "Designation Details Updated Successfully",
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
                'message'=> "Designation Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function designation_view_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.designation.designation_view',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.designation.designation_view_master',compact('menu_data','system_data'));
        }
    }

    public function designation_single_view_page($encrypt_id){

        $id = $this->common->decrypt_data($encrypt_id);

        $designation_data = Designation::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('reference_data.designation.add****reference_data.designation.view');

        $header_status = $this->header_status;

        if(empty($designation_data)){

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

                return view('admin.reference.designation.designation_single_view',compact('menu_data','designation_data','user_right_data','encrypt_id'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.reference.designation.designation_single_view_master',compact('menu_data','designation_data','user_right_data','encrypt_id','system_data'));
            }
        }
    } 

    public function designation_delete_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.designation.designation_delete',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.designation.designation_delete_master',compact('menu_data','system_data'));
        }
    }

    public function designation_delete($encrypt_id){

        $id = $this->common->decrypt_data($encrypt_id);

        $notification = array();

        $data = Designation::where('delete_status',0)
            ->where('id',$id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "Designation Data Not Found!!!",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }
        else{

            $user_session_data = session()->all();

            $user_id = $user_session_data[config('app.app_session_name')]['id'];

            DB::beginTransaction();

            $data->delete_by = $user_id;
            $data->delete_status = 1;
            $data->deleted_at = now();

            $data->save();

            if($data==true){

                $status = $this->common->add_user_activity_history('designations',$data->id,'Delete Designation Details');

                if($status==1){

                    DB::commit();

                    $notification = array(
                        'message'=> "Designation Details Deleted Successfully",
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
                    'message'=> "Designation Details Not Deleted Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.designation.designation_delete_alert',compact('menu_data','notification'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.designation.designation_delete_alert_master',compact('menu_data','notification','system_data'));
        }
    }
}