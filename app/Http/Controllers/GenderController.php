<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Gender;
use DB;

class GenderController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function gender_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.gender.gender_add',compact('menu_data'));
    }

    public function gender_add_store(Request $request){

        $validator = Validator::make($request->all(), [
            'gender_name' => 'required|string|max:250',
            'gender_code' => 'required|string|max:20',
            'gender_title' => 'required|string|max:250'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('gender_name','genders', $request->gender_name, 0);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Gender Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('gender_code','genders', $request->gender_code, 0);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Gender Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new Gender;

        $data->gender_name = $request->gender_name;
        $data->gender_code = $request->gender_code;
        $data->gender_title = $request->gender_title;
        $data->gender_deatils = $request->gender_deatils;
        $data->add_by = $user_id;
        $data->created_at = now();

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Gender Details Created Successfully",
                'alert_type'=>'info',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Gender Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function gender_edit_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.gender.gender_edit',compact('menu_data'));
    }

    public function gender_grid(Request $request){

        $menu_data = $this->common->get_page_menu_grid('reference_data.gender.add');

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

            $total_data = Gender::select('id')
                ->where('delete_status',0)
                ->get();

            $filter_data = Gender::select('id')
                ->where('delete_status',0)
                ->where('gender_name','like',"%".$search_value."%")
                ->orWhere('gender_code','like',"%".$search_value."%")
                ->orWhere('gender_title','like',"%".$search_value."%")
                ->get();

            $data = Gender::select('id','gender_name','gender_code','gender_title','gender_deatils','add_by','edit_by','status')
                ->where('delete_status',0)
                ->where('gender_name','like',"%".$search_value."%")
                ->orWhere('gender_code','like',"%".$search_value."%")
                ->orWhere('gender_title','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = Gender::select('id')
                ->where('delete_status',0)
                ->get();

            $data = Gender::select('id','gender_name','gender_code','gender_title','gender_deatils','add_by','edit_by','status')
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
            $record_data[$sl]['gender_name'] = $value->gender_name;
            $record_data[$sl]['gender_code'] = $value->gender_code;
            $record_data[$sl]['gender_title'] = $value->gender_title;
            $record_data[$sl]['gender_deatils'] = $value->gender_deatils;
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

    public function gender_single_edit_page($id){

        $gender_data = Gender::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('reference_data.gender.add****reference_data.gender.edit');

        return view('admin.reference.gender.gender_edit_view',compact('menu_data','gender_data','user_right_data'));
    }

    public function gender_update($id, Request $request){

        $validator = Validator::make($request->all(), [
            'gender_name' => 'required|string|max:250',
            'gender_code' => 'required|string|max:20',
            'gender_title' => 'required|string|max:250'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('gender_name','genders', $request->gender_name, $request->update_id);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Gender Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('gender_code','genders', $request->gender_code, $request->update_id);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Gender Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = Gender::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->gender_name = $request->gender_name;
        $data->gender_code = $request->gender_code;
        $data->gender_title = $request->gender_title;
        $data->gender_deatils = $request->gender_deatils;
        $data->edit_by = $user_id;
        $data->updated_at = now();
        $data->edit_status = 1;

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Gender Details Updated Successfully",
                'alert_type'=>'info',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Gender Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function gender_view_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.gender.gender_view',compact('menu_data'));
    }

    public function gender_single_view_page($id){

        $gender_data = Gender::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('reference_data.gender.add****reference_data.gender.view');

        return view('admin.reference.gender.gender_single_view',compact('menu_data','gender_data','user_right_data'));
    }

    public function gender_delete_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.gender.gender_delete',compact('menu_data'));
    }

    public function gender_delete($id){

        $notification = array();

        $data = Gender::where('delete_status',0)
            ->where('id',$id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "Gender Data Not Found!!!",
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
                    'message'=> "Gender Details Deleted Successfully",
                    'alert_type'=>'info',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Gender Details Not Deleted Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.gender.gender_delete',compact('menu_data','notification'));
    }
}