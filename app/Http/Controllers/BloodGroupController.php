<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BloodGroup;
use DB;

class BloodGroupController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function blood_group_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.blood_group.blood_group_add',compact('menu_data'));
    }

    public function blood_group_store (Request $request){

        $validator = Validator::make($request->all(), [
            'blood_group_name' => 'required|string|max:250',
            'blood_group_code' => 'required|string|max:20',
            'blood_group_title' => 'required|string|max:250'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('blood_group_name','blood_groups', $request->blood_group_name, 0);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Blood Group Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('blood_group_code','blood_groups', $request->blood_group_code, 0);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Blood Group Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new BloodGroup;

        $data->blood_group_name = $request->blood_group_name;
        $data->blood_group_code = $request->blood_group_code;
        $data->blood_group_title = $request->blood_group_title;
        $data->blood_group_deatils = $request->blood_group_deatils;
        $data->add_by = $user_id;
        $data->created_at = now();

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Blood Group Details Created Successfully",
                'alert_type'=>'info',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Blood Group Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function blood_group_edit_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.blood_group.blood_group_edit',compact('menu_data'));
    }

    public function blood_group_grid(Request $request){

        $menu_data = $this->common->get_page_menu_grid('reference_data.blood_group.add');

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

            $total_data = BloodGroup::select('id')
                ->where('delete_status',0)
                ->get();

            $filter_data = BloodGroup::select('id')
                ->where('delete_status',0)
                ->where('blood_group_name','like',"%".$search_value."%")
                ->orWhere('blood_group_code','like',"%".$search_value."%")
                ->orWhere('blood_group_title','like',"%".$search_value."%")
                ->get();

            $data = BloodGroup::select('id','blood_group_name','blood_group_code','blood_group_title','blood_group_deatils','add_by','edit_by','status')
                ->where('delete_status',0)
                ->where('blood_group_name','like',"%".$search_value."%")
                ->orWhere('blood_group_code','like',"%".$search_value."%")
                ->orWhere('blood_group_title','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = BloodGroup::select('id')
                ->where('delete_status',0)
                ->get();

            $data = BloodGroup::select('id','blood_group_name','blood_group_code','blood_group_title','blood_group_deatils','add_by','edit_by','status')
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
            $record_data[$sl]['blood_group_name'] = $value->blood_group_name;
            $record_data[$sl]['blood_group_code'] = $value->blood_group_code;
            $record_data[$sl]['blood_group_title'] = $value->blood_group_title;
            $record_data[$sl]['blood_group_deatils'] = $value->blood_group_deatils;
            $record_data[$sl]['add_by'] = $value->add_by;
            $record_data[$sl]['edit_by'] = $value->edit_by;
            $record_data[$sl]['status'] = $value->status;
            $record_data[$sl]['action'] = $value->id;
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

    public function blood_group_single_edit_page($id){

        $blood_group_data = BloodGroup::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('reference_data.blood_group.add****reference_data.blood_group.edit');

        return view('admin.reference.blood_group.blood_group_edit_view',compact('menu_data','blood_group_data','user_right_data'));
    }

    public function blood_group_update($id, Request $request){

        $validator = Validator::make($request->all(), [
            'blood_group_name' => 'required|string|max:250',
            'blood_group_code' => 'required|string|max:20',
            'blood_group_title' => 'required|string|max:250'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('blood_group_name','blood_groups', $request->blood_group_name, $request->update_id);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Blood Group Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('blood_group_code','blood_groups', $request->blood_group_code, $request->update_id);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Blood Group Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = BloodGroup::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->blood_group_name = $request->blood_group_name;
        $data->blood_group_code = $request->blood_group_code;
        $data->blood_group_title = $request->blood_group_title;
        $data->blood_group_deatils = $request->blood_group_deatils;
        $data->edit_by = $user_id;
        $data->updated_at = now();
        $data->edit_status = 1;

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Blood Group Details Updated Successfully",
                'alert_type'=>'info',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Blood Group Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function blood_group_view_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.blood_group.blood_group_view',compact('menu_data'));
    }

    public function blood_group_single_view_page($id){

        $blood_group_data = BloodGroup::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('reference_data.blood_group.add****reference_data.blood_group.view');

        return view('admin.reference.blood_group.blood_group_single_view',compact('menu_data','blood_group_data','user_right_data'));
    }

    public function blood_group_delete_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.blood_group.blood_group_delete',compact('menu_data'));
    }

    public function blood_group_delete($id){

        $notification = array();

        $data = BloodGroup::where('delete_status',0)
            ->where('id',$id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "Blood Group Data Not Found!!!",
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

                DB::commit();

                $notification = array(
                    'message'=> "Blood Group Details Deleted Successfully",
                    'alert_type'=>'info',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Blood Group Details Not Deleted Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.blood_group.blood_group_delete_alert',compact('menu_data','notification'));
    }
}
