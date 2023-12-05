@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
	    <div class="row">
	        <!-- left column -->
	        <div class="col-md-12">
	            <!-- general form elements -->
	            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
	                <div class="card-header">
	                    <h3 class="card-title">Add Level Information</h3>
	                </div>
	                <div class="card-header" style="background-color: white;">
	                    {!!$menu_data!!}
	                </div>
	                <!-- /.card-header -->
	                <!-- form start -->
	                <form id="level_form" method="post" autocomplete="off">
	                    @csrf
	                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
	                        <div class="row">
	                            <div class="col-md-3">
	                                <div class="form-group">
	                                    <label for="building_id">Level Building <span style="color:red;">*</span></label>
	                                    <div id="building_id_container"></div>
	                                    <div class="input-error" style="display:none; color: red;" id="building_id_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-3">
	                                <div class="form-group">
	                                    <label for="level_name">Level Name <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control" id="level_name" name="level_name" placeholder="Enter Level Name" onkeyup="check_duplicate_value_with_two_filed('level_name','building_id','Levels',this.value,0,'','');" required>
	                                    <div class="input-error" style="display:none; color: red;" id="level_name_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-3">
	                                <div class="form-group">
	                                    <label for="level_code">Level Code <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control" id="level_code" name="level_code" placeholder="Enter Level Code" onkeyup="check_duplicate_value_with_two_filed('level_code','building_id','Levels',this.value,0,'','');" required>
	                                    <div class="input-error" style="display:none; color: red;" id="level_code_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-3">
	                                <div class="form-group">
	                                    <label for="level_title">Level Title <span style="color:red;">*</span></label>
	                                    <input type="text" class="form-control" id="level_title" name="level_title" placeholder="Enter Level Title" required>
	                                    <div class="input-error" style="display:none; color: red;" id="level_title_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-12">
	                                <div class="form-group">
	                                    <label for="level_deatils">Level Details</label>
	                                    <textarea class="form-control" id="level_deatils" name="level_deatils"></textarea>
	                                    <div class="input-error" style="display:none; color: red;" id="level_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <!-- /.card-body -->
	                    <div class="card-footer">
	                        <button type="button" style="float:right" onclick="save_level_info_data();" class="btn btn-primary">Add Level Information</button>
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

	        $('#level_deatils').summernote({
	            height: 200,
	            focus: true
	        })
	    });

	    function save_level_info_data(){

	        if( form_validation('building_id*level_name*level_code*level_title','Level Building*Level Name*Level Code*Level Title')==false ){

	            return false;
	        }

	        var building_id = $("#building_id").val();
	        var level_name = $("#level_name").val();
	        var level_code = $("#level_code").val();
	        var level_title = $("#level_title").val();
	        var level_deatils = $("#level_deatils").val();

	        var token = $('meta[name="csrf-token"]').attr('content');

	        var form_data = new FormData();

	        form_data.append("building_id", building_id);
	        form_data.append("level_name", level_name);
	        form_data.append("level_code", level_code);
	        form_data.append("level_title", level_title);
	        form_data.append("level_deatils", level_deatils);
	        form_data.append("_token", token);

	        freeze_window(0);

	        http.open("POST","{{route('floor_management.level.add')}}",true);
	        http.setRequestHeader("X-CSRF-TOKEN",token);
	        http.send(form_data);
	        http.onreadystatechange = save_level_info_data_response;
	    }

	    function save_level_info_data_response(){

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

	                        document.getElementById("level_form").reset();

	                        $('#level_deatils').summernote('reset');
	                    }

	                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
	                    $('input[name="_token"]').attr('value', data.csrf_token);
	                }
	            }
	        }
	    }

	    load_drop_down('buildings','id,building_name','building_id','building_id_container','Select Building',0,1,'',0,'');

	</script>
@endsection