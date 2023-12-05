@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
	    <div class="row">
	        <!-- left column -->
	        <div class="col-md-12">
	            <!-- general form elements -->
	            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
	                <div class="card-header">
	                    <h3 class="card-title">View Unit Information - {{$unit_data->unit_name}}</h3>
	                </div>
	                <div class="card-header" style="background-color: white;">
	                    {!!$menu_data!!} 
	                </div>
	                <div class="card" style="margin: 0px !important;">
	                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
	                        <div class="row">
	                            <div class="col-md-6"><p style="margin: 0px !important;" id="building_div_id">Building Name: </p></div>
	                            <div class="col-md-6"><p style="margin: 0px !important;" id="level_div_id">Level Name: </p></div>
	                            <div class="col-md-6"><p style="margin: 0px !important;">Unit Name: {{$unit_data->unit_name}}</p></div>
	                            <div class="col-md-6"><p style="margin: 0px !important;">Unit Code: {{$unit_data->unit_code}}</p></div>
	                            <div class="col-md-6"><p style="margin: 0px !important;">Unit Size(SFT): {{$unit_data->unit_size}}</p></div>
	                            <div class="col-md-6"><p style="margin: 0px !important;">Parking Size(SFT): {{$unit_data->parking_size}}</p></div>
	                            <div class="col-md-6"><p style="margin: 0px !important;">Unit Title: {{$unit_data->unit_title}}</p></div>
	                            <div class="col-md-6"><p style="margin: 0px !important;">unit Status: {{$unit_data->status==1?'Active':'Inactive'}}</p></div>
	                            <div class="col-md-12"><h3>unit Details:</h3>{!!$unit_data->unit_deatils!!}</div>
	                            <div class="col-md-12"><h3>Unit Photo: <img class="rounded avatar-lg" width="100" height="100" src="{{asset('uploads/unit')}}/{{!empty($unit_data->unit_photo)?$unit_data->unit_photo:'unit_photo.png'}}"/></h3></div>
	                            <hr style="border: 1px solid white; width: 100%;">
	                            <div class="col-md-12">
	                                @foreach($user_right_data as $data)
	                                    <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$unit_data->id}}','{{$unit_data->unit_name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
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
	    <script type="text/javascript">
	        get_data_by_id('building_div_id', '{{$unit_data->building_id}}', 'building_name', 'buildings');
	        get_data_by_id('level_div_id', '{{$unit_data->level_id}}', 'level_name', 'levels');
	    </script>
	</div>
@endsection