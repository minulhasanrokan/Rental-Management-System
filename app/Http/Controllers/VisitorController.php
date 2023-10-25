<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Visitor;
use DB;

class VisitorController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function visitor_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.visitor.visitor_add',compact('menu_data'));
    }

    public function visitor_out_page (){

        $menu_data = $this->common->get_page_menu();

        return view('admin.visitor.visitor_out',compact('menu_data'));
    }

    public function visitor_view_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.visitor.visitor_view',compact('menu_data'));
    }

    public function visitor_store(Request $request){

        $validator = Validator::make($request->all(), [
            'entry_date' => 'required',
            'entry_time' => 'required',
            'visitor_name' => 'required',
            'visitor_mobile' => 'required',
            'visitor_address' => 'required',
            'visitor_reason' => 'required',
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required',
            'tenant_name' => 'required',
            'tenant_id' => 'required'
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

        $data = new Visitor;

        $data->entry_date = $request->entry_date;
        $data->entry_time = $request->entry_time;
        $data->visitor_name = $request->visitor_name;
        $data->visitor_mobile = $request->visitor_mobile;
        $data->visitor_address = $request->visitor_address;
        $data->visitor_reason = $request->visitor_reason;
        $data->building_id = $request->building_id;
        $data->level_id = $request->level_id;
        $data->unit_id = $request->unit_id;
        $data->tenant_id = $request->tenant_id;
        $data->tenant_name = $request->tenant_name;
        $data->add_by = $user_id;
        $data->created_at = now();

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Vistors Details Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Vistors Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function visitor_grid (Request $request){
        
        DB::enableQueryLog();

        $menu_data = $this->common->get_page_menu_grid('visitor_management.visitor.add');

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

            $total_data = DB::table('visitors as a')
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
                ->get();

            $filter_data = DB::table('visitors as a')
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
                ->where('c.level_name','like',"%".$search_value."%")
                ->orWhere('b.building_name','like',"%".$search_value."%")
                ->orWhere('d.unit_name','like',"%".$search_value."%")
                ->orWhere('e.name','like',"%".$search_value."%")
                ->orWhere('a.visitor_name','like',"%".$search_value."%")
                ->orWhere('a.visitor_mobile','like',"%".$search_value."%")
                ->get();

            $data = DB::table('visitors as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.tenant_id')
                ->select('a.id', 'a.entry_date', 'a.entry_time', 'a.visitor_name', 'a.visitor_mobile', 'a.visitor_address', 'a.tenant_name', 'a.out_date', 'a.out_time', 'a.visitor_reason', 'b.building_name', 'c.level_name', 'd.unit_name', 'e.name')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->where('c.level_name','like',"%".$search_value."%")
                ->orWhere('b.building_name','like',"%".$search_value."%")
                ->orWhere('d.unit_name','like',"%".$search_value."%")
                ->orWhere('e.name','like',"%".$search_value."%")
                ->orWhere('a.visitor_name','like',"%".$search_value."%")
                ->orWhere('a.visitor_mobile','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = DB::table('visitors as a')
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
                ->get();

            $data = DB::table('visitors as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.tenant_id')
                ->select('a.id', 'a.entry_date', 'a.entry_time', 'a.visitor_name', 'a.visitor_mobile', 'a.visitor_address', 'a.tenant_name', 'a.out_date', 'a.out_time', 'a.visitor_reason', 'b.building_name', 'c.level_name', 'd.unit_name', 'e.name')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
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
            $record_data[$sl]['entry_date'] = $value->entry_date;
            $record_data[$sl]['entry_time'] = $value->entry_time;
            $record_data[$sl]['entry_date_time'] = $value->entry_date." ".$value->entry_time;
            $record_data[$sl]['visitor_name'] = $value->visitor_name;
            $record_data[$sl]['visitor_mobile'] = $value->visitor_mobile;
            $record_data[$sl]['visitor_address'] = $value->visitor_address;
            $record_data[$sl]['tenant_name'] = $value->tenant_name;
            $record_data[$sl]['out_date'] = $value->out_date;
            $record_data[$sl]['out_time'] = $value->out_time;
            $record_data[$sl]['out_date_time'] = $value->out_date." ".$value->out_time;
            $record_data[$sl]['visitor_reason'] = $value->visitor_reason;
            $record_data[$sl]['building_name'] = $value->building_name;
            $record_data[$sl]['level_name'] = $value->level_name;
            $record_data[$sl]['unit_name'] = $value->unit_name;
            $record_data[$sl]['name'] = $value->name;
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

    public function my_visitor_grid (Request $request){
        
        DB::enableQueryLog();

        $menu_data = $this->common->get_page_menu_grid('visitor_management.visitor.add');

        $user_config_data = $this->common->get_user_config_data();

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

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

            $total_data = DB::table('visitors as a')
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
                ->where('a.tenant_id',$user_id)
                ->get();

            $filter_data = DB::table('visitors as a')
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
                ->where('a.tenant_id',$user_id)
                ->where('c.level_name','like',"%".$search_value."%")
                ->orWhere('b.building_name','like',"%".$search_value."%")
                ->orWhere('d.unit_name','like',"%".$search_value."%")
                ->orWhere('e.name','like',"%".$search_value."%")
                ->orWhere('a.visitor_name','like',"%".$search_value."%")
                ->orWhere('a.visitor_mobile','like',"%".$search_value."%")
                ->get();

            $data = DB::table('visitors as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.tenant_id')
                ->select('a.id', 'a.entry_date', 'a.entry_time', 'a.visitor_name', 'a.visitor_mobile', 'a.visitor_address', 'a.tenant_name', 'a.out_date', 'a.out_time', 'a.visitor_reason', 'b.building_name', 'c.level_name', 'd.unit_name', 'e.name')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->where('a.tenant_id',$user_id)
                ->where('c.level_name','like',"%".$search_value."%")
                ->orWhere('b.building_name','like',"%".$search_value."%")
                ->orWhere('d.unit_name','like',"%".$search_value."%")
                ->orWhere('e.name','like',"%".$search_value."%")
                ->orWhere('a.visitor_name','like',"%".$search_value."%")
                ->orWhere('a.visitor_mobile','like',"%".$search_value."%")
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $filter_data = DB::table('visitors as a')
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
                ->where('a.tenant_id',$user_id)
                ->get();

            $data = DB::table('visitors as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.tenant_id')
                ->select('a.id', 'a.entry_date', 'a.entry_time', 'a.visitor_name', 'a.visitor_mobile', 'a.visitor_address', 'a.tenant_name', 'a.out_date', 'a.out_time', 'a.visitor_reason', 'b.building_name', 'c.level_name', 'd.unit_name', 'e.name')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->where('a.tenant_id',$user_id)
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
            $record_data[$sl]['entry_date'] = $value->entry_date;
            $record_data[$sl]['entry_time'] = $value->entry_time;
            $record_data[$sl]['entry_date_time'] = $value->entry_date." ".$value->entry_time;
            $record_data[$sl]['visitor_name'] = $value->visitor_name;
            $record_data[$sl]['visitor_mobile'] = $value->visitor_mobile;
            $record_data[$sl]['visitor_address'] = $value->visitor_address;
            $record_data[$sl]['tenant_name'] = $value->tenant_name;
            $record_data[$sl]['out_date'] = $value->out_date;
            $record_data[$sl]['out_time'] = $value->out_time;
            $record_data[$sl]['out_date_time'] = $value->out_date." ".$value->out_time;
            $record_data[$sl]['visitor_reason'] = $value->visitor_reason;
            $record_data[$sl]['building_name'] = $value->building_name;
            $record_data[$sl]['level_name'] = $value->level_name;
            $record_data[$sl]['unit_name'] = $value->unit_name;
            $record_data[$sl]['name'] = $value->name;
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

    public function visitor_edit_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.visitor.visitor_edit',compact('menu_data'));
    }

    public function visitor_delete_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.visitor.visitor_delete',compact('menu_data'));
    }

    public function visitor_single_edit_page($id){

        $visitor_data = Visitor::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('visitor_management.visitor.add****visitor_management.visitor.edit');

        return view('admin.visitor.visitor_edit_view',compact('menu_data','visitor_data','user_right_data'));
    }

    public function visitor_single_out_page($id){

        $visitor_data = Visitor::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('visitor_management.visitor.add****visitor_management.visitor.out');

        return view('admin.visitor.visitor_out_view',compact('menu_data','visitor_data','user_right_data'));
    }

    public function visitor_update($id, Request $request){

        $validator = Validator::make($request->all(), [
            'entry_date' => 'required',
            'entry_time' => 'required',
            'visitor_name' => 'required',
            'visitor_mobile' => 'required',
            'visitor_address' => 'required',
            'visitor_reason' => 'required',
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required',
            'tenant_name' => 'required',
            'tenant_id' => 'required'
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

        $data = Visitor::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->entry_date = $request->entry_date;
        $data->entry_time = $request->entry_time;
        $data->visitor_name = $request->visitor_name;
        $data->visitor_mobile = $request->visitor_mobile;
        $data->visitor_address = $request->visitor_address;
        $data->visitor_reason = $request->visitor_reason;
        $data->building_id = $request->building_id;
        $data->level_id = $request->level_id;
        $data->unit_id = $request->unit_id;
        $data->tenant_id = $request->tenant_id;
        $data->tenant_name = $request->tenant_name;
        $data->edit_by = $user_id;
        $data->updated_at = now();
        $data->edit_status = 1;

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Vistors Details Updated Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Vistors Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function visitor_out($id, Request $request){

        $validator = Validator::make($request->all(), [
            'entry_date' => 'required',
            'entry_time' => 'required',
            'visitor_name' => 'required',
            'visitor_mobile' => 'required',
            'visitor_address' => 'required',
            'visitor_reason' => 'required',
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required',
            'tenant_name' => 'required',
            'out_date' => 'required',
            'out_time' => 'required'
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

        $data = Visitor::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->entry_date = $request->entry_date;
        $data->entry_time = $request->entry_time;
        $data->visitor_name = $request->visitor_name;
        $data->visitor_mobile = $request->visitor_mobile;
        $data->visitor_address = $request->visitor_address;
        $data->visitor_reason = $request->visitor_reason;
        $data->building_id = $request->building_id;
        $data->level_id = $request->level_id;
        $data->unit_id = $request->unit_id;
        $data->tenant_id = $request->tenant_id;
        $data->tenant_name = $request->tenant_name;
        $data->out_date = $request->out_date;
        $data->out_time = $request->out_time;
        $data->edit_by = $user_id;
        $data->updated_at = now();
        $data->edit_status = 1;

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Vistors Details Updated Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Vistors Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function visitor_delete ($id){

        $notification = array();

        $data = Visitor::where('delete_status',0)
            ->where('id',$id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "Vistors Data Not Found!!!",
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
                    'message'=> "Vistors Details Deleted Successfully",
                    'alert_type'=>'success',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Vistors Details Not Deleted Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }

        $menu_data = $this->common->get_page_menu();

        return view('admin.visitor.visitor_delete_alert',compact('menu_data','notification'));
    }

    public function visitor_single_view_page($id){

        $visitor_data = Visitor::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('visitor_management.visitor.add****visitor_management.visitor.view');

        return view('admin.visitor.visitor_single_view',compact('menu_data','visitor_data','user_right_data'));
    }

    public function my_visitor_single_view_page($id){

        $visitor_data = Visitor::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('visitor_management.my_visitor.view');

        return view('admin.visitor.visitor_single_view',compact('menu_data','visitor_data','user_right_data'));
    }

    public function my_visitor_view_page (){

        $menu_data = $this->common->get_page_menu();

        return view('admin.visitor.my_visitor_view',compact('menu_data'));
    }
}
