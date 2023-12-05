@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
	    <div class="row">
	        <!-- left column -->
	        <div class="col-md-12">
	            <!-- general form elements -->
	            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
	                <div class="card-header">
	                    <h3 class="card-title">Add Unit Information</h3>
	                </div>
	                <div class="card-header" style="background-color: white;">
	                    {!!$menu_data!!}
	                </div>
	                <!-- /.card-header -->
	                <!-- form start -->
	                <form id="unit_form" method="post" autocomplete="off">
	                    @csrf
	                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
	                        <div class="row">
	                            <div class="col-md-2">
	                                <div class="form-group">
	                                    <label for="building_id">Unit Building <span style="color:red;">*</span></label>
	                                    <div id="building_id_container"></div>
	                                    <div class="input-error" style="display:none; color: red;" id="building_id_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-2">
	                                <div class="form-group">
	                                    <label for="level_id">Unit Level <span style="color:red;">*</span></label>
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
	                                    <label for="unit_name">Unit Name <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control" id="unit_name" name="unit_name" placeholder="Enter Unit Name" onkeyup="check_duplicate_value_with_two_filed('unit_name','building_id,level_id','units',this.value,0,'','');" required>
	                                    <div class="input-error" style="display:none; color: red;" id="unit_name_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-2">
	                                <div class="form-group">
	                                    <label for="unit_code">Unit Code <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control" id="unit_code" name="unit_code" placeholder="Enter Unit Code" onkeyup="check_duplicate_value_with_two_filed('unit_code','building_id,level_id','units',this.value,0,'','');" required>
	                                    <div class="input-error" style="display:none; color: red;" id="unit_code_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-2">
	                                <div class="form-group">
	                                    <label for="unit_size">Unit Size (SFT) <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control text_boxes_numeric" id="unit_size" name="unit_size" placeholder="Enter Unit Size" required>
	                                    <div class="input-error" style="display:none; color: red;" id="unit_size_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-2">
	                                <div class="form-group">
	                                    <label for="parking_size">Parking Size (SFT) <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control text_boxes_numeric" id="parking_size" name="parking_size" placeholder="Enter Parking Size" required>
	                                    <div class="input-error" style="display:none; color: red;" id="parking_size_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-12">
	                                <div class="form-group">
	                                    <label for="unit_title">Unit Title <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control" id="unit_title" name="unit_title" placeholder="Enter Unit Title" onkeyup="check_duplicate_value_with_two_filed('unit_title','building_id,level_id','units',this.value,0,'','');" required>
	                                    <div class="input-error" style="display:none; color: red;" id="unit_title_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-12">
	                                <div class="form-group">
	                                    <label for="unit_deatils">Unit Details</label>
	                                    <textarea class="form-control" id="unit_deatils" name="unit_deatils"></textarea>
	                                    <div class="input-error" style="display:none; color: red;" id="unit_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-2">
	                                <div class="form-group">
	                                    <label for="unit_photo">Unit Image <span style="color:red;">*</span></label>
	                                    <input onchange="readUrl(this,'unit_photo_photo');" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" id="unit_photo" name="unit_photo" placeholder="Enter Unit Image" required>
	                                    <input type="hidden" name="hidden_unit_photo" id="hidden_unit_photo" value="">
	                                    <div class="input-error" style="display:none; color: red;" id="unit_photo_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-2">
	                                <div class="form-group">
	                                    <img class="rounded avatar-lg" width="80" height="80" id="unit_photo_photo" src="{{asset('uploads/unit/unit_photo.png')}}"/>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <!-- /.card-body -->
	                    <div class="card-footer">
	                        <button type="button" style="float:right" onclick="save_unit_info_data();" class="btn btn-primary">Add Unit Information</button>
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

	    function save_unit_info_data(){

	        if( form_validation('building_id*level_id*unit_name*unit_code*unit_title*unit_size*parking_size*unit_photo','Unit Building*Unit Level*Unit Name*Unit Code*Unit Title* Unit Size*Parking Size*Unit Image')==false ){

	            return false;
	        }

	        var unit_name = $("#unit_name").val();
	        var building_id = $("#building_id").val();
	        var level_id = $("#level_id").val();
	        var unit_code = $("#unit_code").val();
	        var unit_title = $("#unit_title").val();
	        var unit_size = $("#unit_size").val();
	        var parking_size = $("#parking_size").val();
	        var unit_address = $("#unit_address").val();
	        var unit_deatils = $("#unit_deatils").val();
	        var hidden_unit_photo = $("#hidden_unit_photo").val();

	        var token = $('meta[name="csrf-token"]').attr('content');

	        var form_data = new FormData();

	        var unit_photo = $('#unit_photo')[0].files;
	        form_data.append('unit_photo',unit_photo[0]);

	        form_data.append("building_id", building_id);
	        form_data.append("level_id", level_id);
	        form_data.append("unit_name", unit_name);
	        form_data.append("unit_code", unit_code);
	        form_data.append("unit_title", unit_title);
	        form_data.append("unit_size", unit_size);
	        form_data.append("parking_size", parking_size);
	        form_data.append("unit_address", unit_address);
	        form_data.append("unit_deatils", unit_deatils);
	        form_data.append("hidden_unit_photo", hidden_unit_photo);
	        form_data.append("_token", token);

	        freeze_window(0);

	        http.open("POST","{{route('floor_management.unit.add')}}",true);
	        http.setRequestHeader("X-CSRF-TOKEN",token);
	        http.send(form_data);
	        http.onreadystatechange = save_unit_info_data_response;
	    }

	    function save_unit_info_data_response(){

	        if(http.readyState == 4)
	        {
	            release_freezing();
	            
	            if(http.responseText=='Session Expire' || http.responseText=='Right Not Found'){

	                alert('Session Expire');

	                location.replace('<?php echo url('/dashboard/logout');?>');
	            }
	            else{
	                var data = JSON.parse(http.responseText);

	                if (data.errors && data.success==false) {

	                    $.each(data.errors, function(field, errors) {

	                        $("#" + field + "_error").text(errors);

	                        $("#" + field + "_error").show();
	                    });

	                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
	                    $('input[name="_token"]').attr('value', data.csrf_token);

	                    // hide all input error.............
	                    //$(".input-error").delay(3000).fadeOut(800);
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

	                    if(data.alert_type=='success'){

	                        document.getElementById("unit_form").reset();

	                        $('#unit_deatils').summernote('reset');

	                        $("#unit_photo_photo").attr("src","{{asset('uploads/unit/unit_photo.png')}}");
	                    }

	                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
	                    $('input[name="_token"]').attr('value', data.csrf_token);
	                }
	            }
	        }
	    }

	    load_drop_down('buildings','id,building_name','building_id','building_id_container','Select Building',0,1,'',0,"onchange=\"load_drop_down_by_id('levels','id,level_name','level_id','level_id_container','Select Level',0,1,'',0,this.value,'building_id','','','')\"");

	</script>
@endsection