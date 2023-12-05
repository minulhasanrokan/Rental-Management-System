@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
	    <div class="row">
	        <!-- left column -->
	        <div class="col-md-12">
	            <!-- general form elements -->
	            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
	                <div class="card-header">
	                    <h3 class="card-title">View Unit Information</h3>
	                </div>
	                <div class="card-header" style="background-color: white;">
	                    {!!$menu_data!!} 
	                </div>
	                <div class="card" style="margin: 0px !important;">
	                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
	                        <div class="row">
	                            <div class="col-md-6"><p style="margin: 0px !important;" id="owner_div_id">Owner Name: </p></div>
	                            <div class="col-md-6"><p style="margin: 0px !important;" id="building_div_id">Building Name: </p></div>
	                            <div class="col-md-6"><p style="margin: 0px !important;" id="level_div_id">Level Name: </p></div>
	                            <div class="col-md-6"><p style="margin: 0px !important;" id="unit_div_id">Unit Name: </p></div>
	                            <hr style="border: 1px solid white; width: 100%;">
	                            <div class="col-md-12">
	                                @foreach($user_right_data as $data)
	                                    <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$tag_owner_data->id}}','{{$tag_owner_data->id}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
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
	        get_data_by_id('owner_div_id', '{{$tag_owner_data->owner_id}}', 'name', 'users');
	        get_data_by_id('building_div_id', '{{$tag_owner_data->building_id}}', 'building_name', 'buildings');
	        get_data_by_id('level_div_id', '{{$tag_owner_data->level_id}}', 'level_name', 'levels');
	        get_data_by_id('unit_div_id', '{{$tag_owner_data->unit_id}}', 'unit_name', 'units');
	    </script>
	</div>
@endsection