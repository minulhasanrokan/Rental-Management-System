<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\RentBill;
use DB;

class RentProcessController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function rent_process_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.rent_process.rent_process_add',compact('menu_data'));
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

        foreach($rent_data as $data){

            if (isset($owner_data_arr[$data->building_id][$data->level_id][$data->unit_id])){

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
                $rent_data_arr[$i]['add_by'] = $user_id;
                $rent_data_arr[$i]['created_at'] = now();
                $rent_data_arr[$i]['month_id'] = $month;
                $rent_data_arr[$i]['year_id'] = $year;

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

        return view('admin.rent_process.rent_process_view',compact('menu_data'));
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
                ->where('e.user_type',$user_config_data['tenant_user_type'])
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
                ->where('e.user_type',$user_config_data['tenant_user_type'])
                ->where('c.level_name','like',"%".$search_value."%")
                ->orWhere('b.building_name','like',"%".$search_value."%")
                ->orWhere('d.unit_name','like',"%".$search_value."%")
                ->orWhere('e.name','like',"%".$search_value."%")
                ->orWhere('a.unit_rent','like',"%".$search_value."%")
                ->orWhere('a.water_bill','like',"%".$search_value."%")
                ->orWhere('a.electricity_bill','like',"%".$search_value."%")
                ->orWhere('a.gas_bill','like',"%".$search_value."%")
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
                ->where('e.user_type',$user_config_data['tenant_user_type'])
                ->where('c.level_name','like',"%".$search_value."%")
                ->orWhere('b.building_name','like',"%".$search_value."%")
                ->orWhere('d.unit_name','like',"%".$search_value."%")
                ->orWhere('e.name','like',"%".$search_value."%")
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
                ->where('e.user_type',$user_config_data['tenant_user_type'])
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
            $record_data[$sl]['total'] =  $value->unit_rent+$value->water_bill+$value->electricity_bill+$value->gas_bill+$value->security_bill+$value->maintenance_bill+$value->service_bill+$value->charity_bill+$value->other_bill-$value->discount;
            $record_data[$sl]['building_name'] = $value->building_name;
            $record_data[$sl]['level_name'] = $value->level_name;
            $record_data[$sl]['unit_name'] = $value->unit_name;
            $record_data[$sl]['name'] = $value->name;
            $record_data[$sl]['month_name'] = $value->month_name.'-'.$value->year_id;
            $record_data[$sl]['paid_status'] = $value->paid_status;
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

    public function rent_process_single_view_page($id){

        $rent_bill_data = RentBill::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('rent_management.process.add****rent_management.process.view');

        return view('admin.rent_process.rent_process_single_view',compact('menu_data','rent_bill_data','user_right_data'));
    }
}
