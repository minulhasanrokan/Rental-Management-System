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
}
