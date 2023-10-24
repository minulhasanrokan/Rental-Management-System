<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Rent;
use DB;

class RentalController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function unit_rent_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.rent.rent_add',compact('menu_data'));
    }

    public function get_rent_info_value($value){
        $rent_data = DB::table('unit_rent_information')
            ->select('*')
            ->where('delete_status',0)
            ->where('status',1)
            ->where('id',$value)
            ->get()->toArray();

        return json_encode($rent_data);
    }

    public function unit_rent_store (Request $request){

        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required',
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required',
            'unit_rent' => 'required',
            'water_bill' => 'required',
            'electricity_bill' => 'required',
            'gas_bill' => 'required',
            'security_bill' => 'required',
            'maintenance_bill' => 'required',
            'service_bill' => 'required',
            'charity_bill' => 'required',
            'other_bill' => 'required',
            'start_date' => 'required',
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

        $duplicate_status_1 = $this->common->get_duplicate_value_two('unit_id','building_id,level_id','rents', $request->unit_id, $request->building_id.','.$request->level_id, 0,'close_status','0');

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Unit Rent Information Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new Rent;

        $data->tenant_id = $request->tenant_id;
        $data->building_id = $request->building_id;
        $data->level_id = $request->level_id;
        $data->unit_id = $request->unit_id;
        $data->unit_rent = $request->unit_rent;
        $data->water_bill = $request->water_bill;
        $data->electricity_bill = $request->electricity_bill;
        $data->gas_bill = $request->gas_bill;
        $data->security_bill = $request->security_bill;
        $data->maintenance_bill = $request->maintenance_bill;
        $data->service_bill = $request->service_bill;
        $data->charity_bill = $request->charity_bill;
        $data->other_bill = $request->other_bill;
        $data->start_date = $request->start_date;
        $data->discount = $request->discount;
        $data->add_by = $user_id;
        $data->created_at = now();

        $data->save();

        $data1 = DB::table('units')
            ->where('id', $request->unit_id)
            ->update(['rent_status' => 1]);

        $data2 = DB::table('rent_histories')
            ->insert([
                'rent_id' => $data->id,
                'tenant_id' => $data->tenant_id,
                'building_id' => $data->building_id,
                'level_id' => $data->level_id,
                'unit_id' => $data->unit_id,
                'unit_rent' => $data->unit_rent,
                'water_bill' => $data->water_bill,
                'electricity_bill' => $data->electricity_bill,
                'gas_bill' => $data->gas_bill,
                'security_bill' => $data->security_bill,
                'maintenance_bill' => $data->maintenance_bill,
                'service_bill' => $data->service_bill,
                'charity_bill' => $data->charity_bill,
                'other_bill' => $data->other_bill,
                'start_date' => $data->start_date,
                'discount' => $data->discount,
                'add_by' => $data->add_by,
                'created_at' => $data->created_at
            ]);

        if($data==true && $data1==true){

            DB::commit();

            $notification = array(
                'message'=> "Unit Rent Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Unit Rent Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function rental_view_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.rent.rent_view',compact('menu_data'));
    }

    public function rental_grid (Request $request){
        
        DB::enableQueryLog();

        $menu_data = $this->common->get_page_menu_grid('rent_management.rental.add');

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

            $total_data = DB::table('rents as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.tenant_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->where('e.user_type',$user_config_data['tenant_user_type'])
                ->get();

            $filter_data = DB::table('rents as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.tenant_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->where('e.user_type',$user_config_data['tenant_user_type'])
                ->where('c.level_name','like',"%".$search_value."%")
                ->orWhere('b.building_name','like',"%".$search_value."%")
                ->orWhere('d.unit_name','like',"%".$search_value."%")
                ->orWhere('e.name','like',"%".$search_value."%")
                ->get();

            $data = DB::table('rents as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.tenant_id')
                ->select('a.id', 'a.unit_rent', 'a.water_bill', 'a.electricity_bill', 'a.gas_bill', 'a.security_bill', 'a.maintenance_bill', 'a.service_bill', 'a.charity_bill', 'a.other_bill', 'a.status', 'a.close_status', 'b.building_name', 'c.level_name', 'd.unit_name', 'e.name', 'a.start_date', 'a.close_date', 'a.discount')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->where('e.user_type',$user_config_data['tenant_user_type'])
                ->where('c.level_name','like',"%".$search_value."%")
                ->orWhere('b.building_name','like',"%".$search_value."%")
                ->orWhere('d.unit_name','like',"%".$search_value."%")
                ->orWhere('e.name','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = DB::table('rents as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.tenant_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->where('e.user_type',$user_config_data['tenant_user_type'])
                ->get();

            $data = DB::table('rents as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.tenant_id')
                ->select('a.id', 'a.unit_rent', 'a.water_bill', 'a.electricity_bill', 'a.gas_bill', 'a.security_bill', 'a.maintenance_bill', 'a.service_bill', 'a.charity_bill', 'a.other_bill', 'a.status', 'a.close_status', 'b.building_name', 'c.level_name', 'd.unit_name', 'e.name', 'a.start_date', 'a.close_date', 'a.discount')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->where('e.user_type',$user_config_data['tenant_user_type'])
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();

            $total_data = $filter_data;
        }

        //$query = DB::getQueryLog();
        //dd($query);

        $sl = 0;

        $sl_start = $row+1;

        foreach($data as $value){

            $record_data[$sl]['id'] = $value->id;
            $record_data[$sl]['sl'] = $sl_start;
            $record_data[$sl]['unit_rent'] = $value->unit_rent;
            $record_data[$sl]['water_bill'] = $value->water_bill;
            $record_data[$sl]['electricity_bill'] = $value->electricity_bill;
            $record_data[$sl]['gas_bill'] = $value->gas_bill;
            $record_data[$sl]['other'] = $value->security_bill+$value->maintenance_bill+$value->service_bill+$value->charity_bill+$value->other_bill;
            $record_data[$sl]['total'] = $value->unit_rent+$value->water_bill+$value->electricity_bill+$value->gas_bill+$value->security_bill+$value->maintenance_bill+$value->service_bill+$value->charity_bill+$value->other_bill-$value->discount;
            $record_data[$sl]['building_name'] = $value->building_name;
            $record_data[$sl]['level_name'] = $value->level_name;
            $record_data[$sl]['unit_name'] = $value->unit_name;
            $record_data[$sl]['name'] = $value->name;
            $record_data[$sl]['status'] = $value->status;
            $record_data[$sl]['close_status'] = $value->close_status;
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

    public function rental_edit_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.rent.rent_edit',compact('menu_data'));
    }

    public function rental_delete_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.rent.rent_delete',compact('menu_data'));
    }

    public function rental_close_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.rent.rent_close',compact('menu_data'));
    }

    public function rental_change_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.rent.rent_change',compact('menu_data'));
    }

    public function rental_delete($id){

        $notification = array();

        $data = Rent::where('delete_status',0)
            ->where('id',$id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "Rent Data Not Found!!!",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }
        else{

            $user_session_data = session()->all();

            $user_id = $user_session_data[config('app.app_session_name')]['id'];

            DB::beginTransaction();

            $data1 = DB::table('units')
                ->where('id', $data->unit_id)
                ->update(['rent_status' => 0]);

            $data->delete_by = $user_id;
            $data->delete_status = 1;
            $data->deleted_at = now();

            $data->save();

            if($data==true && $data1==true){

                DB::commit();

                $notification = array(
                    'message'=> "Rent Details Deleted Successfully",
                    'alert_type'=>'success',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Rent Details Not Deleted Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }

        $menu_data = $this->common->get_page_menu();

        return view('admin.rent.rent_delete_alert',compact('menu_data','notification'));
    }

    public function rental_single_edit_page($id){

        $rent_data = Rent::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('rent_management.rental.add****rent_management.rental.edit');

        return view('admin.rent.rent_edit_view',compact('menu_data','rent_data','user_right_data'));
    }

    public function rental_single_close_page($id){

        $rent_data = Rent::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('rent_management.rental.add****rent_management.rental.close');

        return view('admin.rent.rent_close_view',compact('menu_data','rent_data','user_right_data'));
    }

    public function rental_update($id, Request $request){

        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required',
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required',
            'unit_rent' => 'required',
            'water_bill' => 'required',
            'electricity_bill' => 'required',
            'gas_bill' => 'required',
            'security_bill' => 'required',
            'maintenance_bill' => 'required',
            'service_bill' => 'required',
            'charity_bill' => 'required',
            'other_bill' => 'required',
            'start_date' => 'required',
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

        $duplicate_status_1 = $this->common->get_duplicate_value_two('unit_id','building_id,level_id','rents', $request->unit_id, $request->building_id.','.$request->level_id, $request->update_id,'close_status','0');

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Unit Rent Information Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = Rent::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        if($request->unit_id!=$data->unit_id){

            $data1 = DB::table('units')
                ->where('id', $data->unit_id)
                ->update(['rent_status' => 0]);
        }

        $data->tenant_id = $request->tenant_id;
        $data->building_id = $request->building_id;
        $data->level_id = $request->level_id;
        $data->unit_id = $request->unit_id;
        $data->unit_rent = $request->unit_rent;
        $data->water_bill = $request->water_bill;
        $data->electricity_bill = $request->electricity_bill;
        $data->gas_bill = $request->gas_bill;
        $data->security_bill = $request->security_bill;
        $data->maintenance_bill = $request->maintenance_bill;
        $data->service_bill = $request->service_bill;
        $data->charity_bill = $request->charity_bill;
        $data->other_bill = $request->other_bill;
        $data->start_date = $request->start_date;
        $data->discount = $request->discount;
        $data->edit_by = $user_id;
        $data->updated_at = now();
        $data->edit_status = 1;

        $data->save();

        $data2 = DB::table('units')
            ->where('id', $request->unit_id)
            ->update(['rent_status' => 1]);

        $data3 = DB::table('rent_histories')
            ->insert([
                'rent_id' => $data->id,
                'tenant_id' => $data->tenant_id,
                'building_id' => $data->building_id,
                'level_id' => $data->level_id,
                'unit_id' => $data->unit_id,
                'unit_rent' => $data->unit_rent,
                'water_bill' => $data->water_bill,
                'electricity_bill' => $data->electricity_bill,
                'gas_bill' => $data->gas_bill,
                'security_bill' => $data->security_bill,
                'maintenance_bill' => $data->maintenance_bill,
                'service_bill' => $data->service_bill,
                'charity_bill' => $data->charity_bill,
                'other_bill' => $data->other_bill,
                'start_date' => $data->start_date,
                'discount' => $data->discount,
                'add_by' => $data->add_by,
                'edit_by' => $data->edit_by,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,
                'edit_status' => $data->edit_status
            ]);

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Rent Details Updated Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Rent Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function rent_close_update($id, Request $request){

        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required',
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required',
            'unit_rent' => 'required',
            'water_bill' => 'required',
            'electricity_bill' => 'required',
            'gas_bill' => 'required',
            'security_bill' => 'required',
            'maintenance_bill' => 'required',
            'service_bill' => 'required',
            'charity_bill' => 'required',
            'other_bill' => 'required',
            'start_date' => 'required',
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

        $data = Rent::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data1 = DB::table('units')
            ->where('id', $data->unit_id)
            ->update(['rent_status' => 0]);

        $data->edit_by = $user_id;
        $data->close_date = now();
        $data->close_status = 1;
        $data->edit_status = 1;

        $data->save();

        if($data==true && $data1==true){

            DB::commit();

            $notification = array(
                'message'=> "Rent Details Closed Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Rent Details Does Not Closed Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function rental_single_view_page($id){

        $rent_data = Rent::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('rent_management.rental.add****rent_management.rental.view');

        return view('admin.rent.rent_single_view',compact('menu_data','rent_data','user_right_data'));
    }

    public function rental_single_change_page($id){

        $rent_data = Rent::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('rent_management.rental.add****rent_management.rental.change');

        return view('admin.rent.rent_single_change_page',compact('menu_data','rent_data','user_right_data'));
    }

    public function rental_change($id, Request $request){

        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required',
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required',
            'unit_rent' => 'required',
            'water_bill' => 'required',
            'electricity_bill' => 'required',
            'gas_bill' => 'required',
            'security_bill' => 'required',
            'maintenance_bill' => 'required',
            'service_bill' => 'required',
            'charity_bill' => 'required',
            'other_bill' => 'required',
            'start_date' => 'required',
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

        $duplicate_status_1 = $this->common->get_duplicate_value_two('unit_id','building_id,level_id','rents', $request->unit_id, $request->building_id.','.$request->level_id, $request->update_id,'close_status','0');

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Unit Rent Information Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = Rent::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        if($request->unit_id!=$data->unit_id){

            $data1 = DB::table('units')
                ->where('id', $data->unit_id)
                ->update(['rent_status' => 0]);
        }

        $data->tenant_id = $request->tenant_id;
        $data->building_id = $request->building_id;
        $data->level_id = $request->level_id;
        $data->unit_id = $request->unit_id;
        $data->unit_rent = $request->unit_rent;
        $data->water_bill = $request->water_bill;
        $data->electricity_bill = $request->electricity_bill;
        $data->gas_bill = $request->gas_bill;
        $data->security_bill = $request->security_bill;
        $data->maintenance_bill = $request->maintenance_bill;
        $data->service_bill = $request->service_bill;
        $data->charity_bill = $request->charity_bill;
        $data->other_bill = $request->other_bill;
        $data->start_date = $request->start_date;
        $data->discount = $request->discount;
        $data->edit_by = $user_id;
        $data->updated_at = now();
        $data->edit_status = 1;

        $data->save();

        $data2 = DB::table('units')
            ->where('id', $request->unit_id)
            ->update(['rent_status' => 1]);

        $data3 = DB::table('rent_histories')
            ->insert([
                'rent_id' => $data->id,
                'tenant_id' => $data->tenant_id,
                'building_id' => $data->building_id,
                'level_id' => $data->level_id,
                'unit_id' => $data->unit_id,
                'unit_rent' => $data->unit_rent,
                'water_bill' => $data->water_bill,
                'electricity_bill' => $data->electricity_bill,
                'gas_bill' => $data->gas_bill,
                'security_bill' => $data->security_bill,
                'maintenance_bill' => $data->maintenance_bill,
                'service_bill' => $data->service_bill,
                'charity_bill' => $data->charity_bill,
                'other_bill' => $data->other_bill,
                'start_date' => $data->start_date,
                'discount' => $data->discount,
                'add_by' => $data->add_by,
                'edit_by' => $data->edit_by,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,
                'edit_status' => $data->edit_status
            ]);

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Rent Details Changed Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Rent Details Does Not Changed Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

}
