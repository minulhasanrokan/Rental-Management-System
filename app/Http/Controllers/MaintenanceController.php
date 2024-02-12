<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ReferenceCost;
use App\Models\MaintenanceCost;
use DB;

class MaintenanceController extends Controller
{
    public $common;

    public $app_session_name ='';

    public $header_status = 0;

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');

        $this->header_status = $this->common->check_header_info();
    }

    public function reference_cost_add_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.cost.cost_add',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.cost.cost_add_master',compact('menu_data','system_data'));
        }
    }

    public function reference_cost_store (Request $request){

        $validator = Validator::make($request->all(), [
            'cost_name' => 'required|string|max:250',
            'cost_code' => 'required|string|max:20',
            'cost_title' => 'required|string|max:250'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('cost_name','reference_costs', $request->cost_name, 0);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Cost Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('cost_code','reference_costs', $request->cost_code, 0);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Cost Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new ReferenceCost;

        $data->cost_name = $request->cost_name;
        $data->cost_code = $request->cost_code;
        $data->cost_title = $request->cost_title;
        $data->cost_deatils = $request->cost_deatils;
        $data->add_by = $user_id;
        $data->created_at = now();

        $data->save();

        if($data==true){

            $status = $this->common->add_user_activity_history('reference_costs',$data->id,'Add Cost Details');

            if($status==1){

                DB::commit();

                $notification = array(
                    'message'=> "Cost Details Created Successfully",
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
                'message'=> "Cost Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function reference_cost_edit_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.cost.cost_edit',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.cost.cost_edit_master',compact('menu_data','system_data'));
        }
    }

    public function reference_cost_grid(Request $request){

        $menu_data = $this->common->get_page_menu_grid('reference_data.cost.add');

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

            $total_data = ReferenceCost::select('id')
                ->where('delete_status',0)
                ->get();

            $filter_data = ReferenceCost::select('id')
                ->where('delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('cost_name','like',"%".$search_value."%")
                        ->orWhere('cost_code','like',"%".$search_value."%")
                        ->orWhere('cost_title','like',"%".$search_value."%");
                })
                ->get();

            $data = ReferenceCost::select('id','cost_name','cost_code','cost_title','cost_deatils','add_by','edit_by','status')
                ->where('delete_status',0)
                ->where(function ($query) use ($search_value) {   
                    $query->where('cost_name','like',"%".$search_value."%")
                        ->orWhere('cost_code','like',"%".$search_value."%")
                        ->orWhere('cost_title','like',"%".$search_value."%");
                })
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = ReferenceCost::select('id')
                ->where('delete_status',0)
                ->get();

            $data = ReferenceCost::select('id','cost_name','cost_code','cost_title','cost_deatils','add_by','edit_by','status')
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
            $record_data[$sl]['cost_name'] = $value->cost_name;
            $record_data[$sl]['cost_code'] = $value->cost_code;
            $record_data[$sl]['cost_title'] = $value->cost_title;
            $record_data[$sl]['cost_deatils'] = $value->cost_deatils;
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
        $response['csrf_token'] = $csrf_token;

        echo json_encode($response);
    }

    public function reference_cost_single_edit_page($encrypt_id){

        $id = $this->common->decrypt_data($encrypt_id);

        $cost_data = ReferenceCost::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('reference_data.cost.add****reference_data.cost.edit');

        $header_status = $this->header_status;

        if(empty($cost_data)){

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

                return view('admin.reference.cost.cost_edit_view',compact('menu_data','cost_data','user_right_data','encrypt_id'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.reference.cost.cost_edit_view_master',compact('menu_data','cost_data','user_right_data','encrypt_id','system_data'));
            }
        }
    }

    public function reference_cost_update($id, Request $request){

        $validator = Validator::make($request->all(), [
            'cost_name' => 'required|string|max:250',
            'cost_code' => 'required|string|max:20',
            'cost_title' => 'required|string|max:250'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('cost_name','reference_costs', $request->cost_name, $request->update_id);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Cost Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status_2 = $this->common->get_duplicate_value('cost_code','reference_costs', $request->cost_code, $request->update_id);

        if($duplicate_status_2>0){

            $notification = array(
                'message'=> "Duplicate Cost Code Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = ReferenceCost::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->cost_name = $request->cost_name;
        $data->cost_code = $request->cost_code;
        $data->cost_title = $request->cost_title;
        $data->cost_deatils = $request->cost_deatils;
        $data->edit_by = $user_id;
        $data->updated_at = now();
        $data->edit_status = 1;

        $data->save();

        if($data==true){

            $status = $this->common->add_user_activity_history('reference_costs',$data->id,'Update Cost Details');

            if($status==1){

                DB::commit();

                $notification = array(
                    'message'=> "Cost Details Updated Successfully",
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
                'message'=> "Cost Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function reference_cost_view_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.cost.cost_view',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.cost.cost_view_master',compact('menu_data','system_data'));
        }
    }

    public function reference_cost_single_view_page($encrypt_id){

        $id = $this->common->decrypt_data($encrypt_id);

        $cost_data = ReferenceCost::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('reference_data.cost.add****reference_data.cost.view');

        $header_status = $this->header_status;

        if(empty($cost_data)){

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

                return view('admin.reference.cost.cost_single_view',compact('menu_data','cost_data','user_right_data','encrypt_id'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.reference.cost.cost_single_view_master',compact('menu_data','cost_data','user_right_data','encrypt_id','system_data'));
            }
        }
    }

    public function reference_cost_delete_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.cost.cost_delete',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.cost.cost_delete_master',compact('menu_data','system_data'));
        }
    }

    public function reference_cost_delete($encrypt_id){

        $id = $this->common->decrypt_data($encrypt_id);

        $notification = array();

        $data = ReferenceCost::where('delete_status',0)
            ->where('id',$id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "Cost Data Not Found!!!",
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

                $status = $this->common->add_user_activity_history('reference_costs',$data->id,'Delete Cost Details');

                if($status==1){

                    DB::commit();

                    $notification = array(
                        'message'=> "Cost Details Deleted Successfully",
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
                    'message'=> "Cost Details Not Deleted Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.reference.cost.cost_delete_alert',compact('menu_data','notification'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.reference.cost.cost_delete_alert_master',compact('menu_data','notification','system_data'));
        }
    }


    public function maintenance_add_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.maintenance.maintenance_add',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.maintenance.maintenance_add_master',compact('menu_data','system_data'));
        }
    }

    public function maintenance_store (Request $request){

        $validator = Validator::make($request->all(), [
            'cost_date' => 'required',
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required',
            'reference_cost_id' => 'required',
            'cost' => 'required'
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

        $owner_data = $this->common->get_owner_data_by_unit($request->building_id,$request->level_id,$request->unit_id);

        if(empty($owner_data)){

            $notification = array(
                'message'=> "Building Owner Not Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new MaintenanceCost;

        $data->cost_date = $request->cost_date;
        $data->building_id = $request->building_id;
        $data->level_id = $request->level_id;
        $data->unit_id = $request->unit_id;
        $data->reference_cost_id = $request->reference_cost_id;
        $data->owner_id = $owner_data->owner_id;
        $data->cost = $request->cost;
        $data->add_by = $user_id;
        $data->created_at = now();

        if ($request->hasFile('cost_file')) {

            $file = $request->file('cost_file');

            $extension = $file->getClientOriginalExtension();

            $fileName = 'cost_file_'.time().'.'.$extension;

            $file->move('uploads/cost_file/', $fileName);

            $data->cost_file = $fileName;
        }

        $data->save();

        if($data==true){

            $status = $this->common->add_user_activity_history('maintenance_costs',$data->id,'Add Maintenance Costs Details');

            if($status==1){

                DB::commit();

                $notification = array(
                    'message'=> "Maintenance Costs Details Created Successfully",
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
                'message'=> "Maintenance Costs Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}
