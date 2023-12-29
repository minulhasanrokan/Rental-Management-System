@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
	    <div class="row">
	        <!-- left column -->
	        <div class="col-md-12">
	            <!-- general form elements -->
	            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
	                <div class="card-header">
	                    <h3 class="card-title">Add Out Visitor Information</h3>
	                </div>
	                <div class="card-header" style="background-color: white;">
	                    {!!$menu_data!!}
	                </div>
	                <!-- /.card-header -->
	                <!-- form start -->
	                <form id="visitor_form" method="post" autocomplete="off">
	                    @csrf
	                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
	                        <div class="row">
	                            <div class="col-md-3">
	                                <div class="form-group">
	                                    <label for="entry_date">Entry Date <span style="color:red;">*</span></label>
	                                    <input type="date" class="form-control" id="entry_date" name="entry_date" placeholder="Enter Entry Date" value="{{$visitor_data->entry_date}}" required>
	                                    <div class="input-error" style="display:none; color: red;" id="entry_date_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-3">
	                                <div class="form-group">
	                                    <label for="entry_time">Entry Time <span style="color:red;">*</span></label>
	                                    <input type="time" class="form-control" id="entry_time" name="entry_time" placeholder="Enter Entry Date" value="{{$visitor_data->entry_time}}" required>
	                                    <div class="input-error" style="display:none; color: red;" id="entry_time_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-3">
	                                <div class="form-group">
	                                    <label for="visitor_name">Visitor Name <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control" id="visitor_name" name="visitor_name" placeholder="Enter Visitor Name" value="{{$visitor_data->visitor_name}}" required>
	                                    <div class="input-error" style="display:none; color: red;" id="visitor_name_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-3">
	                                <div class="form-group">
	                                    <label for="visitor_mobile">Visitor Mobile <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control" id="visitor_mobile" name="visitor_mobile" placeholder="Enter Visitor Mobile" value="{{$visitor_data->visitor_mobile}}" required>
	                                    <div class="input-error" style="display:none; color: red;" id="visitor_mobile_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-12">
	                                <div class="form-group">
	                                    <label for="visitor_address">Visitor Address <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control" id="visitor_address" name="visitor_address" placeholder="Enter Visitor Address" value="{{$visitor_data->visitor_address}}" required>
	                                    <div class="input-error" style="display:none; color: red;" id="visitor_address_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-12">
	                                <div class="form-group">
	                                    <label for="visitor_reason">Visit Reason</label>
	                                    <textarea class="form-control" id="visitor_reason" name="visitor_reason">{{$visitor_data->visitor_reason}}</textarea>
	                                    <div class="input-error" style="display:none; color: red;" id="visitor_reason_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-2">
	                                <div class="form-group">
	                                    <label for="building_id">Building <span style="color:red;">*</span></label>
	                                    <div id="building_id_container"></div>
	                                    <div class="input-error" style="display:none; color: red;" id="building_id_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-2">
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
	                            <div class="col-md-2">
	                                <div class="form-group">
	                                    <label for="unit_id">Unit <span style="color:red;">*</span></label>
	                                    <div id="unit_id_container">
	                                        <select class="form-control select" style="width: 100%; float: left;" name="unit_id" id="unit_id">
	                                            <option value="">Select Unit</option>
	                                        </select>
	                                    </div>
	                                    <div class="input-error" style="display:none; color: red;" id="unit_id_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-2">
	                                <div class="form-group">
	                                    <label for="tenant_name">Tenant Name <span style="color:red;">*</span></label>
	                                    <input type="text" readonly class="form-control" id="tenant_name" name="tenant_name" placeholder="Enter Tenant Name" required>
	                                    <input type="hidden" readonly class="form-control" id="tenant_id" name="tenant_id" required>
	                                    <div class="input-error" style="display:none; color: red;" id="tenant_name_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-2">
	                                <div class="form-group">
	                                    <label for="out_date">Out Date <span style="color:red;">*</span></label>
	                                    <input type="date" class="form-control" id="out_date" name="out_date" placeholder="Enter Out Date" value="{{$visitor_data->out_date}}" required>
	                                    <div class="input-error" style="display:none; color: red;" id="out_date_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-2">
	                                <div class="form-group">
	                                    <label for="out_time">Out Time <span style="color:red;">*</span></label>
	                                    <input type="time" class="form-control" id="out_time" name="out_time" placeholder="Enter Out Date" value="{{$visitor_data->out_time}}" required>
	                                    <div class="input-error" style="display:none; color: red;" id="out_time_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <!-- /.card-body -->
	                    <div class="card-footer">
	                        @foreach($user_right_data as $data)
	                            <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$encrypt_id}}','{{$visitor_data->visitor_name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
	                        @endforeach
	                        <button type="button" style="float:right" onclick="save_visitor_info_data();" class="btn btn-primary">Out Rent Information</button>
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

	        $('#visitor_reason').summernote({
	            height: 64,
	            focus: true
	        })
	    });

	    function save_visitor_info_data(){

	        if( form_validation('entry_date*entry_time*visitor_name*visitor_mobile*visitor_address*visitor_reason*building_id*level_id*unit_id*tenant_name*tenant_id*out_date*out_time','Entry Date*Entry Time*Visitor Name*Visitor Mobile*Visitor Address*Visit Reason*Building Name*Level Name*Unit Name*Tenant Name*Tenant Name*Out Data*Out Time')==false ){

	            return false;
	        }

	        var entry_date = $("#entry_date").val();
	        var entry_time = $("#entry_time").val();
	        var visitor_name = $("#visitor_name").val();
	        var visitor_mobile = $("#visitor_mobile").val();
	        var visitor_address = $("#visitor_address").val();
	        var visitor_reason = $("#visitor_reason").val();
	        var building_id = $("#building_id").val();
	        var level_id = $("#level_id").val();
	        var unit_id = $("#unit_id").val();
	        var tenant_name = $("#tenant_name").val();
	        var tenant_id = $("#tenant_id").val();
	        var out_date = $("#out_date").val();
	        var out_time = $("#out_time").val();

	        var token = $('meta[name="csrf-token"]').attr('content');

	        var form_data = new FormData();

	        form_data.append("entry_date", entry_date);
	        form_data.append("entry_time", entry_time);
	        form_data.append("visitor_name", visitor_name);
	        form_data.append("visitor_mobile", visitor_mobile);
	        form_data.append("visitor_address", visitor_address);
	        form_data.append("visitor_reason", visitor_reason);
	        form_data.append("building_id", building_id);
	        form_data.append("level_id", level_id);
	        form_data.append("unit_id", unit_id);
	        form_data.append("tenant_name", tenant_name);
	        form_data.append("tenant_id", tenant_id);
	        form_data.append("out_date", out_date);
	        form_data.append("out_time", out_time);
	        form_data.append("update_id", {{$visitor_data->id}});

	        form_data.append("_token", token);

	        freeze_window(0);

	        http.open("POST","{{route('visitor_management.visitor.out',$visitor_data->id)}}",true);
	        http.setRequestHeader("X-CSRF-TOKEN",token);
	        http.send(form_data);
	        http.onreadystatechange = save_visitor_info_data_response;
	    }

	    function save_visitor_info_data_response(){

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

	    load_drop_down('buildings','id,building_name','building_id','building_id_container','Select Building',0,1,'{{$visitor_data->building_id}}',1,'onchange="load_drop_down_by_id(\'levels\',\'id,level_name\',\'level_id\',\'level_id_container\',\'Select Level\',0,1,\'\',0,this.value,\'building_id\',\'onchange=get_unit_load_drop_down_by_id(this.value)\',\'\',\'\')"');

	    load_drop_down_by_id('levels','id,level_name','level_id','level_id_container','Select Level',0,1,'{{$visitor_data->level_id}}',1,'{{$visitor_data->building_id}}','building_id','onchange=get_unit_load_drop_down_by_id(this.value)','','');

	    load_drop_down_by_id('units','id,unit_name','unit_id','unit_id_container','Select Unit',0,1,'{{$visitor_data->unit_id}}',1,'{{$visitor_data->level_id}}','level_id',"onchange=\"get_tenant_data(this.value);\"",'rent_status','1');

	    function get_unit_load_drop_down_by_id(value){

	        load_drop_down_by_id('units','id,unit_name','unit_id','unit_id_container','Select Unit',0,1,'',0,value,'level_id',"onchange=\" get_tenant_data(this.value);\"",'rent_status','1');
	    }

	    function get_tenant_data(value){

	        if(value=='' || value==0)
	        {
	            alert("Please Select Unit First");
	            return false;
	        }

	        var data="&value="+value;

	        if(value=='')
	        {
	            value =0;
	        }
	 
	        http.open("GET","{{route('admin.get.current.tenant.info.by.unit')}}"+"/"+value,true);
	        http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	        http.send(data);
	        http.onreadystatechange = get_tenant_data_reponse;
	    }

	    function get_tenant_data_reponse(){

	        if(http.readyState == 4)
	        {
	            var reponse=trim(http.responseText).split("\*\*\*\*");

	            if(reponse[0]=='Session Expire' || reponse[0]=='Right Not Found'){

	                alert(http.responseText);

	                location.replace('<?php echo url('/dashboard/logout');?>');
	            }
	            else{

	                var data = JSON.parse(reponse[0]);

	                if(data.length==0){

	                    alert("No Tenant Information Found");
	                    return false;
	                }
	                else{

	                    $("#tenant_id").val(data[0]['tenant_id']);
	                    $("#tenant_name").val(data[0]['tenant_name']);
	                }
	            }
	        }
	    }

	    get_tenant_data('{{$visitor_data->unit_id}}');

	</script>
@endsection