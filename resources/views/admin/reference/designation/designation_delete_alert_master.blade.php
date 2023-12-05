@extends('admin.admin_master')

@section('content')
    @php
    
    $action_name = request()->route()->getName();

    $right_data = DB::table('right_details')
        ->select('r_route_name','r_title','r_icon','r_name')
        ->where('right_details.r_route_name',$action_name)
        ->where('right_details.status',1)
        ->where('right_details.delete_status',0)
        ->first();
    @endphp

    <div class="container-fluid" style="padding-top: 5px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                    <div class="card-header">
                        <h3 class="card-title">Delete Designation Information</h3>
                    </div>
                    <div class="card-header" style="background-color: white;">
                        {!!$menu_data!!}
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="text-align: center;">
                        <h3 style="text-align: center; color: red">{{$notification['message']}}</h3>
                        <button onclick="get_new_page('{{route($right_data->r_route_name)}}','{{$right_data->r_title}}','','');" type="button" class="btn btn-primary"><i class="fa {{$right_data->r_icon}}"></i>&nbsp;{{$right_data->r_name}}</button>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
    </div>
@endsection