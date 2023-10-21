<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\VatTax;
use DB;

class VatTaxController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function tax_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.tax.tax_add',compact('menu_data'));
    }

    public function tax_store (Request $request){

        $validator = Validator::make($request->all(), [
            'year_id' => 'required',
            'month_id' => 'required',
            'tax_amount' => 'required'
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

        if($request->tax_amount>100){

            if($duplicate_status_1>0){

                $notification = array(
                    'message'=> "Vat Tax Amount Can Not Be Grater Then 100",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );

                return response()->json($notification);
            }
        }

        $duplicate_status_1 = $this->common->get_duplicate_value_two('month_id','year_id','vat_taxes', $request->month_id, $request->year_id, 0,'','');

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Vat Tax Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new VatTax;

        $data->year_id = $request->year_id;
        $data->month_id = $request->month_id;
        $data->tax_amount = $request->tax_amount;
        $data->add_by = $user_id;
        $data->created_at = now();

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Vat Tax Details Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Vat Tax Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function tax_view_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.tax.tax_view',compact('menu_data'));
    }

    public function tax_grid(Request $request){
        
        DB::enableQueryLog();

        $menu_data = $this->common->get_page_menu_grid('reference_data.tax.add');

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

            $total_data = DB::table('vat_taxes as a')
                ->join('months as b', 'b.id', '=', 'a.month_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->get();

            $filter_data = DB::table('vat_taxes as a')
                ->join('months as b', 'b.id', '=', 'a.month_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('a.year_id','like',"%".$search_value."%")
                ->where('a.tax_amount','like',"%".$search_value."%")
                ->orWhere('b.name','like',"%".$search_value."%")
                ->get();

            $data = DB::table('vat_taxes as a')
                ->join('months as b', 'b.id', '=', 'a.month_id')
                ->select('a.id', 'a.month_id', 'a.year_id', 'a.tax_amount', 'b.name', 'a.status')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('a.year_id','like',"%".$search_value."%")
                ->where('a.tax_amount','like',"%".$search_value."%")
                ->orWhere('b.name','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = DB::table('vat_taxes as a')
                ->join('months as b', 'b.id', '=', 'a.month_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->get();

            $data = DB::table('vat_taxes as a')
                ->join('months as b', 'b.id', '=', 'a.month_id')
                ->select('a.id', 'a.month_id', 'a.year_id', 'a.tax_amount', 'b.name', 'a.status')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
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
            $record_data[$sl]['month_id'] = $value->month_id;
            $record_data[$sl]['year_id'] = $value->year_id;
            $record_data[$sl]['tax_amount'] = $value->tax_amount;
            $record_data[$sl]['name'] = $value->name;
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

    public function tax_edit_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.tax.tax_edit',compact('menu_data'));
    }

    public function tax_delete_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.tax.tax_delete',compact('menu_data'));
    }

    public function tax_single_edit_page($id){

        $tax_data = VatTax::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('reference_data.tax.add****reference_data.tax.edit');

        return view('admin.reference.tax.tax_edit_view',compact('menu_data','tax_data','user_right_data'));
    }

    public function tax_update($id, Request $request){

        $validator = Validator::make($request->all(), [
            'year_id' => 'required',
            'month_id' => 'required',
            'tax_amount' => 'required'
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

        if($request->tax_amount>100){

            if($duplicate_status_1>0){

                $notification = array(
                    'message'=> "Vat Tax Amount Can Not Be Grater Then 100",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );

                return response()->json($notification);
            }
        }

        $duplicate_status_1 = $this->common->get_duplicate_value_two('month_id','year_id','vat_taxes', $request->month_id, $request->year_id, $request->update_id,'','');

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Vat Tax Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = VatTax::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->month_id = $request->month_id;
        $data->year_id = $request->year_id;
        $data->tax_amount = $request->tax_amount;
        $data->edit_by = $user_id;
        $data->updated_at = now();
        $data->edit_status = 1;

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Vat Tax Details Updated Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Vat Tax Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function tax_single_view_page($id){

        $tax_data = VatTax::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('reference_data.tax.add****reference_data.tax.view');

        return view('admin.reference.tax.tax_single_view',compact('menu_data','tax_data','user_right_data'));
    }

    public function tax_delete($id){

        $notification = array();

        $data = VatTax::where('delete_status',0)
            ->where('id',$id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "Vat Tax Data Not Found!!!",
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
                    'message'=> "Vat Tax Details Deleted Successfully",
                    'alert_type'=>'success',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Vat Tax Details Not Deleted Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }

        $menu_data = $this->common->get_page_menu();

        return view('admin.reference.tax.tax_delete_alert',compact('menu_data','notification'));
    }
}
