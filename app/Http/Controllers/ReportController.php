<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class ReportController extends Controller
{
    public $common;

    public $app_session_name ='';

    public $header_status = 0;

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');

        $this->header_status = $this->common->check_header_info();
    }

    public function user_activity_report_page(){

        $menu_data = $this->common->get_page_menu();

        $header_status = $this->header_status;

        if($header_status==1){

            return view('admin.report.user_activity_report',compact('menu_data'));
        }
        else{

            $system_data = $this->common->get_system_data();

            return view('admin.report.user_activity_report_master',compact('menu_data','system_data'));
        }
    }

    public function user_activity_report (Request $request){

        $validator = Validator::make($request->all(), [
            'group_id' => 'required',
            'category_id' => 'required',
            'right_id' => 'required',
            'from_date' => 'required',
            'to_date' => 'required'
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

        $status = $this->common->add_user_activity_history('','0','Download User Activity Report','group_id####'.$request->group_id.'****category_id####'.$request->category_id.'****group_id####'.$request->group_id.'****group_id####'.$request->group_id.'****group_id####'.$request->group_id);

        $report_data = DB::table('activity_histories as a')
            ->join('right_groups as b', 'b.id', '=', 'a.group_id')
            ->join('right_categories as c', 'c.id', '=', 'a.category_id')
            ->join('right_details as d', 'd.id', '=', 'a.right_id')
            ->join('users as e', 'e.id', '=', 'a.add_by')
            ->select('a.*','b.name as group_name','c.c_name as category_name','d.r_name as right_name','e.name as user_name')
            ->where('a.status',1)
            ->where('a.delete_status',0)
            ->where('a.group_id',$request->group_id)
            ->where('a.category_id',$request->category_id)
            ->where('a.right_id',$request->right_id)
            ->whereBetween(DB::raw('DATE_FORMAT(a.created_at, "%Y-%m-%d")'), [$request->from_date, $request->to_date])
            ->orderBy('a.id', 'desc')
            ->get()->toArray();

        $data = array(
            'report_data'=> $report_data,
            'csrf_token' => csrf_token()
        );

        return response()->json($data);
    }
}