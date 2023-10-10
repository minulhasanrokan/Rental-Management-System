<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Models\TagOwner;
use DB;

class TagOwnerController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function tag_owner_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.floor.tag_owner.tag_owner_add',compact('menu_data'));
    }

    public function tag_owner_store (Request $request){

        $validator = Validator::make($request->all(), [
            'owner_id' => 'required',
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('unit_id','tag_owners', $request->unit_id, 0);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Data Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = new TagOwner;

        $data->owner_id = $request->owner_id;
        $data->building_id = $request->building_id;
        $data->level_id = $request->level_id;
        $data->unit_id = $request->unit_id;
        $data->add_by = $user_id;
        $data->created_at = now();

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Tag Owner Details Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Tag Owner Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function tag_owner_edit_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.floor.tag_owner.tag_owner_edit',compact('menu_data'));
    }

    public function tag_owner_grid(Request $request){
        
        DB::enableQueryLog();

        $menu_data = $this->common->get_page_menu_grid('floor_management.tag_unit_owner.add');

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

            $total_data = DB::table('tag_owners as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.owner_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->get();

            $filter_data = DB::table('tag_owners as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.owner_id')
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
                ->get();

            $data = DB::table('tag_owners as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.owner_id')
                ->select('a.id', 'e.name','b.building_name', 'c.level_name', 'd.unit_name','a.status')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
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

            $filter_data = DB::table('tag_owners as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.owner_id')
                ->select('a.id')
                ->where('a.delete_status',0)
                ->where('b.delete_status',0)
                ->where('c.delete_status',0)
                ->where('d.delete_status',0)
                ->where('e.delete_status',0)
                ->get();

            $data = DB::table('tag_owners as a')
                ->join('buildings as b', 'b.id', '=', 'a.building_id')
                ->join('levels as c', 'c.id', '=', 'a.level_id')
                ->join('units as d', 'd.id', '=', 'a.unit_id')
                ->join('users as e', 'e.id', '=', 'a.owner_id')
                ->select('a.id', 'e.name','b.building_name', 'c.level_name', 'd.unit_name','a.status')
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
            $record_data[$sl]['name'] = $value->name;
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

    public function tag_owner_single_edit_page($id){

        $tag_owner_data = TagOwner::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('floor_management.tag_unit_owner.add****floor_management.tag_unit_owner.edit');

        return view('admin.floor.tag_owner.tag_owner_edit_view',compact('menu_data','tag_owner_data','user_right_data'));
    }

    public function tag_owner_update($id, Request $request){

        $validator = Validator::make($request->all(), [
            'owner_id' => 'required',
            'building_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required'
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

        $duplicate_status_1 = $this->common->get_duplicate_value('unit_id','tag_owners', $request->unit_id, $request->update_id);

        if($duplicate_status_1>0){

            $notification = array(
                'message'=> "Duplicate Data Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $user_session_data = session()->all();

        $user_id = $user_session_data[config('app.app_session_name')]['id'];

        DB::beginTransaction();

        $data = TagOwner::where('delete_status',0)
            ->where('id',$request->update_id)
            ->first();

        $data->owner_id = $request->owner_id;
        $data->building_id = $request->building_id;
        $data->level_id = $request->level_id;
        $data->unit_id = $request->unit_id;
        $data->edit_by = $user_id;
        $data->updated_at = now();
        $data->edit_status = 1;

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Tag Owner Details Updated Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Tag Owner Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function tag_owner_delete_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.floor.tag_owner.tag_owner_delete',compact('menu_data'));
    }

    public function tag_owner_delete($id){

        $notification = array();

        $data = TagOwner::where('delete_status',0)
            ->where('id',$id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "Tag Owner Data Not Found!!!",
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
                    'message'=> "Tag Owner Details Deleted Successfully",
                    'alert_type'=>'success',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                DB::rollBack();

                $notification = array(
                    'message'=> "Tag Owner Details Not Deleted Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }

        $menu_data = $this->common->get_page_menu();

        return view('admin.floor.tag_owner.tag_owner_delete_alert',compact('menu_data','notification'));
    }

    public function tag_owner_view_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.floor.tag_owner.tag_owner_view',compact('menu_data'));
    }

    public function tag_owner_single_view_page($id){

        $tag_owner_data = TagOwner::where('delete_status',0)
            ->where('id',$id)
            ->first();
    
        $menu_data = $this->common->get_page_menu();

        $user_right_data = $this->common->get_page_menu_single_view('floor_management.tag_unit_owner.add****floor_management.tag_unit_owner.view');

        return view('admin.floor.tag_owner.tag_owner_single_view',compact('menu_data','tag_owner_data','user_right_data'));
    }
}
