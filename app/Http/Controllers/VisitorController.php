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
}
