<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Models\Notice;
use DB;


class NoticeController extends Controller
{
    public $common;

    public $app_session_name ='';

    public function __construct(){

        $this->common = new CommonController();

        $this->app_session_name = config('app.app_session_name');
    }

    public function notice_add_page(){

        $menu_data = $this->common->get_page_menu();

        return view('admin.notice.notice_add',compact('menu_data'));
    }

    public function notice_store (Request $request){

        $validator = Validator::make($request->all(), [
            'notice_title' => 'required|string|max:250',
            'notice_date' => 'required|date',
            'notice_status' => 'required',
            'notice_details' => 'required'
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

        $data = new Notice;

        $data->notice_title = $request->notice_title;
        $data->notice_group = $request->notice_group;
        $data->user_id = $request->user_id;
        $data->notice_date = $request->notice_date;
        $data->notice_status = $request->notice_status;
        $data->notice_details = $request->notice_details;
        $data->add_by = $user_id;
        $data->created_at = now();

        if ($request->hasFile('notice_file')) {

            $file = $request->file('notice_file');

            $extension = $file->getClientOriginalExtension();

            $fileName = 'notice_'.time().'.'.$extension;

            $file->move('uploads/notice/', $fileName);

            $data->notice_file = $fileName;
        }

        $data->save();

        if($data==true){

            DB::commit();

            $notification = array(
                'message'=> "Notice Details Created Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            DB::rollBack();

            $notification = array(
                'message'=> "Notice Details Does Not Created Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }
}
