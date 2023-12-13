@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                    <div class="card-header">
                        <h3 class="card-title">View User Group Information - {{$group_data->group_name}}</h3>
                    </div>
                    <div class="card-header" style="background-color: white;">
                        {!!$menu_data!!} 
                    </div>
                    <div class="card" style="margin: 0px !important;">
                        <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                            <div class="row">
                                <div class="col-md-6"><h3>User Group Name: {{$group_data->group_name}}</h3></div>
                                <div class="col-md-6"><h3>User Group Code: {{$group_data->group_code}}</h3></div>
                                <div class="col-md-6"><h3>User Group Title: {{$group_data->group_title}}</h3></div>
                                <div class="col-md-6"><h3>User Group Status: {{$group_data->status==1?'Active':'Inactive'}}</h3></div>
                                <div class="col-md-12"><h3>User Group Details:</h3>{!!$group_data->group_deatils!!}</div>
                                <div class="col-md-6"><h3>User Group Image: <img class="rounded avatar-lg" width="80" height="80" id="group_logo_photo" src="{{asset('uploads/user_group')}}/{{!empty($group_data->group_logo)?$group_data->group_logo:'user_group.png'}}"/></h3></div>
                                <div class="col-md-6"><h3>User Group Icon: <img class="rounded avatar-lg" width="80" height="80" id="group_logo_photo" src="{{asset('uploads/user_group')}}/{{!empty($group_data->group_icon)?$group_data->group_icon:'user_group.png'}}"/></h3></div>
                                <hr style="border: 1px solid white; width: 100%;">
                                <div class="col-md-12">
                                    @foreach($user_right_data as $data)
                                        <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$encrypt_id}}','{{$group_data->group_name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
    </div>
@endsection