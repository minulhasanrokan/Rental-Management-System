@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
	    <div class="row">
	        <!-- left column -->
	        <div class="col-md-12">
	            <!-- general form elements -->
	            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
	                <div class="card-header">
	                    <h3 class="card-title">Edit Unit Rent Information</h3>
	                </div>
	                <div class="card-header" style="background-color: white;">
	                    {!!$menu_data!!}
	                </div>
	                <!-- /.card-header -->
	                <!-- form start -->
	                <form id="unit_rent_form" method="post" autocomplete="off">
	                    @csrf
	                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
	                        <div class="row">
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                    <label for="building_id">Building <span style="color:red;">*</span></label>
	                                    <div id="building_id_container"></div>
	                                    <div class="input-error" style="display:none; color: red;" id="building_id_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                    <label for="level_id">Level <span style="color:red;">*</span></label>
	                                    <div id="level_id_container">
	                                        <select class="form-control select" style="width: 100%;" name="level_id" id="level_id">
	                                            <option value="">Select Level</option>
	                                        </select>
	                                    </div>
	                                    <div class="input-error" style="display:none; color: red;" id="level_id_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                    <label for="unit_id">Unit <span style="color:red;">*</span></label>
	                                    <div id="unit_id_container">
	                                        <select class="form-control select" style="width: 100%;" name="unit_id" id="unit_id">
	                                            <option value="">Select Unit</option>
	                                        </select>
	                                    </div>
	                                    <div class="input-error" style="display:none; color: red;" id="unit_id_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                    <label for="unit_rent">Rent <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control text_boxes_numeric" id="unit_rent" name="unit_rent" placeholder="Enter Rent" value="{{$unit_rent_data->unit_rent}}" required>
	                                    <div class="input-error" style="display:none; color: red;" id="unit_rent_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                    <label for="water_bill">Water Bill <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control text_boxes_numeric" id="water_bill" name="water_bill" placeholder="Enter Water Bill" value="{{$unit_rent_data->water_bill}}"  required>
	                                    <div class="input-error" style="display:none; color: red;" id="water_bill_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                    <label for="electricity_bill">Electricity Bill <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control text_boxes_numeric" id="electricity_bill" name="electricity_bill" placeholder="Enter Electricity Bill" value="{{$unit_rent_data->electricity_bill}}"  required>
	                                    <div class="input-error" style="display:none; color: red;" id="electricity_bill_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                    <label for="gas_bill">Gas Bill <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control text_boxes_numeric" id="gas_bill" name="gas_bill" placeholder="Enter Gas Bill" value="{{$unit_rent_data->gas_bill}}"  required>
	                                    <div class="input-error" style="display:none; color: red;" id="gas_bill_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                    <label for="security_bill">Security Bill <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control text_boxes_numeric" id="security_bill" name="security_bill" placeholder="Enter Security Bill" value="{{$unit_rent_data->security_bill}}"  required>
	                                    <div class="input-error" style="display:none; color: red;" id="security_bill_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                    <label for="maintenance_bill">Maintenance Bill <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control text_boxes_numeric" id="maintenance_bill" name="maintenance_bill" placeholder="Enter Maintenance Bill" value="{{$unit_rent_data->maintenance_bill}}" required>
	                                    <div class="input-error" style="display:none; color: red;" id="maintenance_bill_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                    <label for="service_bill">Service Charge <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control text_boxes_numeric" id="service_bill" name="service_bill" placeholder="Enter Service Charge" value="{{$unit_rent_data->service_bill}}"  required>
	                                    <div class="input-error" style="display:none; color: red;" id="service_bill_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                    <label for="charity_bill">Charity Fund <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control text_boxes_numeric" id="charity_bill" name="charity_bill" placeholder="Enter Charity Fund" value="{{$unit_rent_data->charity_bill}}"  required>
	                                    <div class="input-error" style="display:none; color: red;" id="charity_bill_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                    <label for="other_bill">Other Bill <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control text_boxes_numeric" id="other_bill" name="other_bill" placeholder="Enter Other Bill" value="{{$unit_rent_data->other_bill}}"  required>
	                                    <div class="input-error" style="display:none; color: red;" id="other_bill_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <!-- /.card-body -->
	                    <div class="card-footer">
	                        @foreach($user_right_data as $data)
	                            <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$encrypt_id}}','{{$unit_rent_data->unit_rent}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
	                        @endforeach
	                        <button type="button" style="float:right" onclick="update_unit_rent_info_data();" class="btn btn-primary">Update Unit Rent Information</button>
	                    </div>
	                </form>
	            </div>
	            <!-- /.card -->
	        </div>
	        <!--/.col (left) -->
	    </div>
	    <!-- /.row -->
	</div>

	<script>
	    $(function () {

	        $('#unit_deatils').summernote({
	            height: 60,
	            focus: true
	        })
	    });

	    function update_unit_rent_info_data(){

	        if( form_validation('building_id*level_id*unit_id*unit_rent*water_bill*electricity_bill*gas_bill*security_bill*maintenance_bill*service_bill*charity_bill*other_bill','Building Name*Level Name*Unit Name*Unit Rent*Water Bill*Electricity Bill*Gas Bill*Security Bill*Maintenance Bill*Service Charge*Charity Found*Other Bill')==false ){

	            return false;
	        }

	        var building_id = $("#building_id").val();
	        var level_id = $("#level_id").val();
	        var unit_id = $("#unit_id").val();
	        var unit_rent = $("#unit_rent").val();
	        var water_bill = $("#water_bill").val();
	        var electricity_bill = $("#electricity_bill").val();
	        var gas_bill = $("#gas_bill").val();
	        var security_bill = $("#security_bill").val();
	        var maintenance_bill = $("#maintenance_bill").val();
	        var service_bill = $("#service_bill").val();
	        var charity_bill = $("#charity_bill").val();
	        var other_bill = $("#other_bill").val();

	        var token = $('meta[name="csrf-token"]').attr('content');

	        var form_data = new FormData();

	        form_data.append("building_id", building_id);
	        form_data.append("level_id", level_id);
	        form_data.append("unit_id", unit_id);
	        form_data.append("unit_rent", unit_rent);
	        form_data.append("water_bill", water_bill);
	        form_data.append("electricity_bill", electricity_bill);
	        form_data.append("gas_bill", gas_bill);
	        form_data.append("security_bill", security_bill);
	        form_data.append("maintenance_bill", maintenance_bill);
	        form_data.append("service_bill", service_bill);
	        form_data.append("charity_bill", charity_bill);
	        form_data.append("other_bill", other_bill);
	        form_data.append("update_id", '{{$unit_rent_data->id}}');
	        form_data.append("_token", token);

	        freeze_window(0);

	        http.open("POST","{{route('floor_management.unit_rent.edit',$unit_rent_data->id)}}",true);
	        http.setRequestHeader("X-CSRF-TOKEN",token);
	        http.send(form_data);
	        http.onreadystatechange = update_unit_rent_info_data_response;
	    }

	    function update_unit_rent_info_data_response(){

	        if(http.readyState == 4)
	        {
	            release_freezing();

	            if(http.responseText=='Session Expire' || http.responseText=='Right Not Found'){

	                alert('Session Expire');

	                location.replace('<?php echo url('/dashboard/logout');?>');
	            }
	            else{

	                var data = JSON.parse(http.responseText);

	                $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                	$('input[name="_token"]').attr('value', data.csrf_token);

	                if (data.errors && data.success==false) {

	                    $.each(data.errors, function(field, errors) {

	                        $("#" + field + "_error").text(errors);

	                        $("#" + field + "_error").show();
	                    });
	                }
	                else{

	                    switch(data.alert_type){

	                        case 'info':
	                        toastr.info(data.message);
	                        break;

	                        case 'success':
	                        toastr.success(data.message);
	                        break;

	                        case 'warning':
	                        toastr.warning(data.message);
	                        break;

	                        case 'error':
	                        toastr.error(data.message);
	                        break; 
	                    }
	                }

	                // hide all input error.............
                	$(".input-error").delay(3000).fadeOut(800);
	            }
	        }
	    }

	    load_drop_down('buildings','id,building_name','building_id','building_id_container','Select Building',0,1,'{{$unit_rent_data->building_id}}',1,'onchange="load_drop_down_by_id(\'levels\',\'id,level_name\',\'level_id\',\'level_id_container\',\'Select Level\',0,1,\'\',0,this.value,\'building_id\',\'onchange=get_unit_load_drop_down_by_id(this.value)\',\'\',\'\')"');

	    load_drop_down_by_id('levels','id,level_name','level_id','level_id_container','Select Level',0,1,'{{$unit_rent_data->level_id}}',1,'{{$unit_rent_data->building_id}}','building_id',"onchange=\"check_duplicate_value_with_two_filed('unit_id','building_id,level_id','unit_rent_information',this.value,0,'',''); get_unit_load_drop_down_by_id(this.value);\"",'','');

	    load_drop_down_by_id('units','id,unit_name','unit_id','unit_id_container','Select Unit',0,1,'{{$unit_rent_data->unit_id}}',1,'{{$unit_rent_data->level_id}}','level_id',"onchange=\"check_duplicate_value_with_two_filed('unit_id','building_id,level_id','unit_rent_information',this.value,0,'','');\"",'','');

	    function get_unit_load_drop_down_by_id(value)
	    {

	        load_drop_down_by_id('units','id,unit_name','unit_id','unit_id_container','Select Unit',0,1,'',0,value,'level_id',"onchange=\"check_duplicate_value_with_two_filed('unit_id','building_id,level_id','unit_rent_information',this.value,0,'','');\"",'','');
	    }

	</script>
@endsection