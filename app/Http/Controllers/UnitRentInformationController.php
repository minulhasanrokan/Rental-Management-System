<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UnitRentInformation;
use DB;

class UnitRentInformationController extends Controller
{
    public $common;

    public $app_session_name ='';

    public $header_status = 0;

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');

        $this->header_status = $this->common->check_header_info();
    }

    public function unit_rent_add_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.floor.unit_rent.unit_rent_add',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.floor.unit_rent.unit_rent_add_master',compact('menu_data','system_data'));
        }
    }

    public function unit_rent_store (Request $request){

        $validator = Validator::make($request->all(), [
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

        $duplicate_status_1 = $this->common->get_duplicate_value_two('unit_id','building_id,level_id','unit_rent_information', $request->unit_id, $request->building_id.','.$request->level_id, 0);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Unit Rent Information Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new UnitRentInformation;

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
        $data->add_by = $user_id;
        $data->created_at = now();

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Unit Rent Details Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Unit Rent Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function unit_rent_edit_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.floor.unit_rent.unit_rent_edit',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.floor.unit_rent.unit_rent_edit_master',compact('menu_data','system_data'));
        }
    }

    public function unit_rent_grid(Request $request){
        
        DB::enableQueryLog();

        $menu_data = $this->common->get_page_menu_grid('floor_management.unit_rent.add');

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

            $total_data = DB::table('unit_rent_information as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                //->where('b.id','c.building_id')
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->get();

            $filter_data = DB::table('unit_rent_information as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                //->where('b.id','c.building_id')
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('c.level_name','like',"%".$search_value."%")
                ->orWhere('b.building_name','like',"%".$search_value."%")
                ->orWhere('d.unit_name','like',"%".$search_value."%")
                ->orWhere('a.unit_rent','like',"%".$search_value."%")
                ->orWhere('a.water_bill','like',"%".$search_value."%")
                ->orWhere('a.electricity_bill','like',"%".$search_value."%")
                ->orWhere('a.gas_bill','like',"%".$search_value."%")
                ->get();

            $data = DB::table('unit_rent_information as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->select('a.id', 'a.unit_rent', 'a.water_bill', 'a.electricity_bill', 'a.gas_bill', 'a.security_bill', 'a.maintenance_bill', 'a.service_bill', 'a.charity_bill', 'a.other_bill', 'a.status','b.building_name', 'c.level_name', 'd.unit_name')
                ->where('a.delete_status',0)
                //->where('b.id','c.building_id')
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('c.level_name','like',"%".$search_value."%")
                ->orWhere('b.building_name','like',"%".$search_value."%")
                ->orWhere('d.unit_name','like',"%".$search_value."%")
                ->orWhere('a.unit_rent','like',"%".$search_value."%")
                ->orWhere('a.water_bill','like',"%".$search_value."%")
                ->orWhere('a.electricity_bill','like',"%".$search_value."%")
                ->orWhere('a.gas_bill','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = DB::table('unit_rent_information as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                //->where('b.id','c.building_id')
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->get();

            $data = DB::table('unit_rent_information as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->select('a.id', 'a.unit_rent', 'a.water_bill', 'a.electricity_bill', 'a.gas_bill', 'a.security_bill', 'a.maintenance_bill', 'a.service_bill', 'a.charity_bill', 'a.other_bill', 'a.status','b.building_name', 'c.level_name', 'd.unit_name')
                ->where('a.delete_status',0)
                //->where('b.id','c.building_id')
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
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
            $record_data[$sl]['building_name'] = $value->building_name;
            $record_data[$sl]['level_name'] = $value->level_name;
            $record_data[$sl]['unit_name'] = $value->unit_name;
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

    public function unit_rent_single_edit_page($id){

        $unit_rent_data = UnitRentInformation::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('floor_management.unit_rent.add****floor_management.unit_rent.edit');

        $header_status = $this->header_status;

        if(empty($unit_rent_data)){

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

                return view('admin.floor.unit_rent.unit_rent_edit_view',compact('menu_data','unit_rent_data','user_right_data'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.floor.unit_rent.unit_rent_edit_view_master',compact('menu_data','unit_rent_data','user_right_data','system_data'));
            }
        }
    }

    public function unit_rent_update($id, Request $request){

        $validator = Validator::make($request->all(), [
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

        $duplicate_status_1 = $this->common->get_duplicate_value_two('unit_id','building_id,level_id','unit_rent_information', $request->unit_id, $request->building_id.','.$request->level_id, $request->update_id);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Unit Rent Information Name Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = UnitRentInformation::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

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
        $data->edit_by = $user_id;
        $data->updated_at = now();
        $data->edit_status = 1;

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Unit Rent Details Updated Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Unit Rent Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function unit_rent_delete_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.floor.unit_rent.unit_rent_delete',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.floor.unit_rent.unit_rent_delete_master',compact('menu_data','system_data'));
        }
    }

    public function unit_rent_delete($id){

        $notification = array();

        $data = UnitRentInformation::where('delete_status',0)
            ->where('id',$id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "Unit Rent Data Not Found!!!",
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
                    'message'=> "Unit Rent Details Deleted Successfully",
                    'alert_type'=>'success',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Unit Rent Details Not Deleted Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.floor.unit_rent.unit_rent_delete_alert',compact('menu_data','notification'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.floor.unit_rent.unit_rent_delete_alert_master',compact('menu_data','notification','system_data'));
        }
    }

    public function unit_rent_view_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.floor.unit_rent.unit_rent_view',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.floor.unit_rent.unit_rent_view_master',compact('menu_data','system_data'));
        }
    }

    public function unit_rent_single_view_page($id){

        $unit_rent_data = UnitRentInformation::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('floor_management.unit_rent.add****floor_management.unit_rent.view');

        $header_status = $this->header_status;

        if(empty($unit_rent_data)){

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

                return view('admin.floor.unit_rent.unit_rent_single_view',compact('menu_data','unit_rent_data','user_right_data'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.floor.unit_rent.unit_rent_single_view_master',compact('menu_data','unit_rent_data','user_right_data','system_data'));
            }
        }
    }
}
