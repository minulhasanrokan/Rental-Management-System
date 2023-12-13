@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                    <div class="card-header">
                        <h3 class="card-title">View Designation Information - {{$designation_data->designation_name}}</h3>
                    </div>
                    <div class="card-header" style="background-color: white;">
                        {!!$menu_data!!} 
                    </div>
                    <div class="card" style="margin: 0px !important;">
                        <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                            <div class="row">
                                <div class="col-md-6"><h3>Designation Name: {{$designation_data->designation_name}}</h3></div>
                                <div class="col-md-6"><h3>Designation Code: {{$designation_data->designation_code}}</h3></div>
                                <div class="col-md-6"><h3>Designation Title: {{$designation_data->designation_title}}</h3></div>
                                <div class="col-md-6"><h3>Designation Status: {{$designation_data->status==1?'Active':'Inactive'}}</h3></div>
                                <div class="col-md-12"><h3>Designation Details:</h3>{!!$designation_data->designation_deatils!!}</div>
                                <hr style="border: 1px solid white; width: 100%;">
                                <div class="col-md-12">
                                    @foreach($user_right_data as $data)
                                        <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$encrypt_id}}','{{$designation_data->designation_name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
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