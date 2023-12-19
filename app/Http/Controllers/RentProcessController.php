<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\RentBill;
use App\Models\VatTax;
use DB;
use PDF;

class RentProcessController extends Controller
{
    public $common;

    public $app_session_name ='';

    public $header_status = 0;

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');

        $this->header_status = $this->common->check_header_info();
    }

    public function rent_process_add_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.rent_process.rent_process_add',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.rent_process.rent_process_add_master',compact('menu_data','system_data'));
        }
    }

    public function rent_process_store (Request $request){

        $validator = Validator::make($request->all(), [
            'month_id' => 'required',
            'year_id' => 'required'
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

        $month = $request->month_id;
        $year = $request->year_id;

        $tax_data = VatTax::where('delete_status',0)
            ->where('month_id',$month)
            ->where('year_id',$year)
            ->where('status',1)
            ->first();

        if(empty($tax_data)){

            $notification = array(
                'message'=> "No Vat Tax Information Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $rent_bill_data = DB::table('rent_bills as a')
            ->select('a.*')
            ->where('a.status',1)
            ->where('a.delete_status',0)
            ->where('a.month_id',$month)
            ->where('a.year_id',$year)
            ->get()->toArray();

        if(!empty($rent_bill_data)){

            $notification = array(
                'message'=> "Alredy Process This Month Data",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_config_data = $this->common->get_user_config_data();

        $last_date_of_month = date('Y-m-d', strtotime("$year-$month-01 +1 month -1 day"));

        $first_date_of_curr_month = date('Y-m-01');

        if($last_date_of_month>$first_date_of_curr_month){

            $notification = array(
                'message'=> "You Can Not Process Advance Month Data",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $owner_data = DB::table('tag_owners as a')
            ->join('users as b', 'b.id', '=', 'a.owner_id')
            ->select('a.id', 'a.owner_id', 'a.building_id', 'a.level_id', 'a.unit_id', 'b.name')
            ->where('a.status',1)
            ->where('a.delete_status',0)
            ->where('b.status',1)
            ->where('b.delete_status',0)
            ->where('b.user_type',$user_config_data['owner_user_type'])
            ->get()->toArray();

        $owner_data_arr = array();

        foreach($owner_data as $data){

            $owner_data_arr[$data->building_id][$data->level_id][$data->unit_id] = $data->owner_id;
        }

        $rent_data = DB::table('rents as a')
            ->select('a.*')
            ->where('a.status',1)
            ->where('a.delete_status',0)
            ->where('a.close_status',0)
            ->where('a.start_date','<=',$last_date_of_month)
            ->get()->toArray();

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        $rent_data_arr = array();

        $i=0;

        $total_amount = 0;
        $vat_amount = 0;
        $tax_amount = $tax_data->tax_amount;

        foreach($rent_data as $data){

            if (isset($owner_data_arr[$data->building_id][$data->level_id][$data->unit_id])){

                $total_amount = ($data->unit_rent+$data->water_bill+$data->electricity_bill+$data->gas_bill+$data->security_bill+$data->maintenance_bill+$data->service_bill+$data->charity_bill+$data->other_bill-$data->discount);

                $vat_amount = ($tax_amount / 100) * $total_amount;

                $total_amount = $total_amount+$vat_amount;

                $rent_data_arr[$i]['tenant_id'] = $data->tenant_id;
                $rent_data_arr[$i]['building_id'] = $data->building_id;
                $rent_data_arr[$i]['level_id'] = $data->level_id;
                $rent_data_arr[$i]['unit_id'] = $data->unit_id;
                $rent_data_arr[$i]['unit_rent'] = $data->unit_rent;
                $rent_data_arr[$i]['water_bill'] = $data->water_bill;
                $rent_data_arr[$i]['electricity_bill'] = $data->electricity_bill;
                $rent_data_arr[$i]['gas_bill'] = $data->gas_bill;
                $rent_data_arr[$i]['security_bill'] = $data->security_bill;
                $rent_data_arr[$i]['maintenance_bill'] = $data->maintenance_bill;
                $rent_data_arr[$i]['service_bill'] = $data->service_bill;
                $rent_data_arr[$i]['charity_bill'] = $data->charity_bill;
                $rent_data_arr[$i]['other_bill'] = $data->other_bill;
                $rent_data_arr[$i]['discount'] = $data->discount;
                $rent_data_arr[$i]['tax_amount'] = $tax_amount;
                $rent_data_arr[$i]['total_amount'] = $total_amount;
                $rent_data_arr[$i]['add_by'] = $user_id;
                $rent_data_arr[$i]['month_id'] = $month;
                $rent_data_arr[$i]['invoice_no'] = '';
                $rent_data_arr[$i]['year_id'] = $year;
                $rent_data_arr[$i]['created_at'] = now();

                $rent_data_arr[$i]['owner_id'] = $owner_data_arr[$data->building_id][$data->level_id][$data->unit_id];

                $i++;
            }
        }

        if(!empty($rent_data_arr))
        {

            DB::beginTransaction();

            $data1 = DB::table('rent_bills')
                ->insert($rent_data_arr);

            if($data1==true){

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
        }
        else{

            $notification = array(
                'message'=> "No Data Found For Process",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function rent_process_view_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.rent_process.rent_process_view',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.rent_process.rent_process_view_master',compact('menu_data','system_data'));
        }
    }

    public function rent_process_grid(Request $request){
        
        DB::enableQueryLog();

        $menu_data = $this->common->get_page_menu_grid('rent_management.process.add');

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

            $total_data = DB::table('rent_bills as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.tenant_id')
                ->join('months as f', 'f.id', '=', 'a.month_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->where('f.delete_status',0)
                ->get();

            $filter_data = DB::table('rent_bills as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.tenant_id')
                ->join('months as f', 'f.id', '=', 'a.month_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->where('f.delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('c.level_name','like',"%".$search_value."%")
                        ->orWhere('b.building_name','like',"%".$search_value."%")
                        ->orWhere('d.unit_name','like',"%".$search_value."%")
                        ->orWhere('e.name','like',"%".$search_value."%")
                        ->orWhere('a.unit_rent','like',"%".$search_value."%")
                        ->orWhere('a.water_bill','like',"%".$search_value."%")
                        ->orWhere('a.electricity_bill','like',"%".$search_value."%")
                        ->orWhere('a.gas_bill','like',"%".$search_value."%");
                })
                ->get();

            $data = DB::table('rent_bills as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.tenant_id')
                ->join('months as f', 'f.id', '=', 'a.month_id')
                ->select('a.id', 'a.unit_rent', 'a.water_bill', 'a.electricity_bill', 'a.gas_bill', 'a.security_bill', 'a.maintenance_bill', 'a.service_bill', 'a.charity_bill', 'a.other_bill', 'a.status','b.building_name', 'c.level_name', 'd.unit_name', 'e.name', 'a.paid_status', 'a.discount', 'f.name as month_name', 'a.year_id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->where('f.delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('c.level_name','like',"%".$search_value."%")
                        ->orWhere('b.building_name','like',"%".$search_value."%")
                        ->orWhere('d.unit_name','like',"%".$search_value."%")
                        ->orWhere('e.name','like',"%".$search_value."%")
                        ->orWhere('a.unit_rent','like',"%".$search_value."%")
                        ->orWhere('a.water_bill','like',"%".$search_value."%")
                        ->orWhere('a.electricity_bill','like',"%".$search_value."%")
                        ->orWhere('a.gas_bill','like',"%".$search_value."%");
                })
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = DB::table('rent_bills as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.tenant_id')
                ->join('months as f', 'f.id', '=', 'a.month_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->where('f.delete_status',0)
                ->get();

            $data = DB::table('rent_bills as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.tenant_id')
                ->join('months as f', 'f.id', '=', 'a.month_id')
                ->select('a.id', 'a.unit_rent', 'a.water_bill', 'a.electricity_bill', 'a.gas_bill', 'a.security_bill', 'a.maintenance_bill', 'a.service_bill', 'a.charity_bill', 'a.other_bill', 'a.status','b.building_name', 'c.level_name', 'd.unit_name', 'e.name', 'a.paid_status', 'a.discount', 'f.name as month_name', 'a.year_id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->where('f.delete_status',0)
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
            $record_data[$sl]['total'] =  $value->unit_rent+$value->water_bill+$value->electricity_bill+$value->gas_bill+$value->security_bill+$value->maintenance_bill+$value->service_bill+$value->charity_bill+$value->other_bill-$value->discount;
            $record_data[$sl]['building_name'] = $value->building_name;
            $record_data[$sl]['level_name'] = $value->level_name;
            $record_data[$sl]['unit_name'] = $value->unit_name;
            $record_data[$sl]['name'] = $value->name;
            $record_data[$sl]['month_name'] = $value->month_name.'-'.$value->year_id;
            $record_data[$sl]['paid_status'] = $value->paid_status;
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

    public function rent_process_single_view_page($encrypt_id){

        $id = $this->common->decrypt_data($encrypt_id);

        $rent_bill_data = RentBill::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('rent_management.process.add****rent_management.process.view****rent_management.process.rent_collection');

        $header_status = $this->header_status;

        if(empty($rent_bill_data)){

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

                return view('admin.rent_process.rent_process_single_view',compact('menu_data','rent_bill_data','user_right_data','encrypt_id'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.rent_process.rent_process_single_view_master',compact('menu_data','rent_bill_data','user_right_data','encrypt_id','system_data'));
            }
        }
    }

    public function rent_invoice_view_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.rent_process.rent_invoice_view',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.rent_process.rent_invoice_view_master',compact('menu_data','system_data'));
        }
    }

    public function rent_invoice_single_view_page($encrypt_id){

        $id = $this->common->decrypt_data($encrypt_id);

        $rent_bill_data = RentBill::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $system_data = $this->common->get_system_data();
        $user_data = $this->common->get_data_by_id('users',$rent_bill_data->tenant_id,'');
        $month_data = $this->common->get_data_by_id('months',$rent_bill_data->month_id,'');

        $building_data = $this->common->get_data_by_id('buildings',$rent_bill_data->building_id,'');
        $level_data = $this->common->get_data_by_id('levels',$rent_bill_data->level_id,'');
        $unit_data = $this->common->get_data_by_id('units',$rent_bill_data->unit_id,'');

        $user_right_data = $this->common->get_page_menu_single_view('rent_management.process.add****rent_management.process.invoice****rent_management.process.rent_collection');

        $header_status = $this->header_status;

        if(empty($rent_bill_data)){

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

                return view('admin.rent_process.rent_invoice_single_view',compact('menu_data','rent_bill_data','user_right_data','system_data','user_data','month_data','building_data','level_data','unit_data','encrypt_id'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.rent_process.rent_invoice_single_view_master',compact('menu_data','rent_bill_data','user_right_data','system_data','user_data','month_data','building_data','level_data','unit_data','encrypt_id','system_data'));
            }
        }
    }

    public function rent_collection_add_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.rent_process.rent_collection_add',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.rent_process.rent_collection_add_master',compact('menu_data','system_data'));
        }
    }

    public function rent_collection_single_edit_page($encrypt_id){

        $id = $this->common->decrypt_data($encrypt_id);

        $rent_bill_data = RentBill::where('delete_status',0)
            ->where('id',$id)
            ->first(); 
    
        $menu_data = $this->common->get_page_menu();

        $system_data = $this->common->get_system_data();
        $user_data = $this->common->get_data_by_id('users',$rent_bill_data->tenant_id,'');
        $month_data = $this->common->get_data_by_id('months',$rent_bill_data->month_id,'');

        $building_data = $this->common->get_data_by_id('buildings',$rent_bill_data->building_id,'');
        $level_data = $this->common->get_data_by_id('levels',$rent_bill_data->level_id,'');
        $unit_data = $this->common->get_data_by_id('units',$rent_bill_data->unit_id,'');

        $user_right_data = $this->common->get_page_menu_single_view('rent_management.process.add****rent_management.process.rent_collection****rent_management.process.rent_collection');

        $header_status = $this->header_status;

        if(empty($rent_bill_data)){

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

                return view('admin.rent_process.rent_collection_single_view',compact('menu_data','rent_bill_data','user_right_data','system_data','user_data','month_data','building_data','level_data','unit_data','encrypt_id'));
            }
            else{

                $system_data = $this->common->get_system_data();

                return view('admin.rent_process.rent_collection_single_view_master',compact('menu_data','rent_bill_data','user_right_data','system_data','user_data','month_data','building_data','level_data','unit_data','encrypt_id','system_data'));
            }
        }
    }

    public function rent_invoice_single_print($encrypt_id,$status){

        $id = $this->common->decrypt_data($encrypt_id);

        $rent_bill_data = RentBill::where('delete_status',0)
            ->where('id',$id)
            ->first();

        $system_data = $this->common->get_system_data();
        $user_data = $this->common->get_data_by_id('users',$rent_bill_data->tenant_id,'');
        $month_data = $this->common->get_data_by_id('months',$rent_bill_data->month_id,'');

        $building_data = $this->common->get_data_by_id('buildings',$rent_bill_data->building_id,'');
        $level_data = $this->common->get_data_by_id('levels',$rent_bill_data->level_id,'');
        $unit_data = $this->common->get_data_by_id('units',$rent_bill_data->unit_id,'');

        if($status==0){

            return view('admin.rent_process.invoice_print',compact('rent_bill_data','system_data','user_data','month_data','building_data','level_data','unit_data','encrypt_id'));
        }
        else{

            $pdf = PDF::loadView('admin.rent_process.invoice_print',compact('rent_bill_data','system_data','user_data','month_data','building_data','level_data','unit_data','encrypt_id'));
     
            return $pdf->download($rent_bill_data->invoice_no.'.pdf');
        }
    }

    public function rent_collection_update ($id, Request $request){

        $validator = Validator::make($request->all(), [
            'update_id' => 'required'
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

        $data = RentBill::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->paid_status = 1;
        $data->receive_by = $user_id;
        $data->payment_date = now();

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Rent Bill Paid Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Rent Bill Does Not Paid Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}
