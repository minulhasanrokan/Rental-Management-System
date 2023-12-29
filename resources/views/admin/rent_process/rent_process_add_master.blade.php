@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
	    <div class="row">
	        <!-- left column -->
	        <div class="col-md-12">
	            <!-- general form elements -->
	            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
	                <div class="card-header">
	                    <h3 class="card-title">Rent Information Process</h3>
	                </div>
	                <div class="card-header" style="background-color: white;">
	                    {!!$menu_data!!}
	                </div>
	                <!-- /.card-header -->
	                <!-- form start -->
	                <form id="rent_process_form" method="post" autocomplete="off">
	                    @csrf
	                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
	                        <div class="row">
	                            <div class="col-md-6">
	                                <div class="form-group">
	                                    <label for="month_id">Process Month <span style="color:red;">*</span></label>
	                                    <div id="month_id_container"></div>
	                                    <div class="input-error" style="display:none; color: red;" id="month_id_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                            <div class="col-md-6">
	                                <div class="form-group">
	                                    <label for="year_id">Process Year <span style="color:red;">*</span></label>
	                                    <div id="year_id_container">
	                                        <select class="form-control select" style="width: 100%;" name="year_id" id="year_id">
	                                            <option value="">Select Year</option>
	                                        </select>
	                                    </div>
	                                    <div class="input-error" style="display:none; color: red;" id="year_id_error" style="display: inline-block; width:100%; color: red;"></div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <!-- /.card-body -->
	                    <div class="card-footer">
	                        <button type="button" style="float:right" onclick="process_rent_info_data();" class="btn btn-primary">Process Rent Information</button>
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

	    function process_rent_info_data(){

	        if( form_validation('month_id*year_id','Process Month*Process Year')==false ){

	            return false;
	        }

	        var today = new Date();
	        var firstDayOfCurrentMonth = new Date(today.getFullYear(), today.getMonth(), 1);

	        var month_id = $("#month_id").val();
	        var year_id = $("#year_id").val();

	        var month = parseInt(month_id);
	        var year = parseInt(year_id);

	        var today = new Date(); 
	        var currentYear = today.getFullYear();
	        var currentMonth = today.getMonth() + 1;

	        if(year>currentYear){

	            alert("You Can Not Process Advance Year Data");
	            
	            $("#year_id").val('');
	            $("#year_id").focus();
	            
	            return false;
	        }

	        var lastDayOfTheMonth = new Date(year, month, 0);

	        var last_date_of_month = lastDayOfTheMonth.toISOString().slice(0, 10);

	        var today = new Date();  // Get the current date

	        today.setDate(1);

	        var formattedFirstDay = today.toISOString().slice(0, 10);

	        if(last_date_of_month>formattedFirstDay){

	            alert("You Can Not Process Advance Month Data");

	            $("#month_id").val('');
	            $("#year_id").val('');

	            $("#month_id").focus();
	            
	            return false;
	        }

	        var token = $('meta[name="csrf-token"]').attr('content');

	        var form_data = new FormData();

	        form_data.append("month_id", month_id);
	        form_data.append("year_id", year_id);

	        form_data.append("_token", token);

	        freeze_window(0);

	        http.open("POST","{{route('rent_management.process.add')}}",true);
	        http.setRequestHeader("X-CSRF-TOKEN",token);
	        http.send(form_data);
	        http.onreadystatechange = process_rent_info_data_response;
	    }

	    function process_rent_info_data_response(){

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

	                    if(data.alert_type=='success'){

	                        document.getElementById("rent_process_form").reset();
	                    }
	                }

	                // hide all input error.............
                	$(".input-error").delay(3000).fadeOut(800);
	            }
	        }
	    }

	    load_drop_down('months','id,name','month_id','month_id_container','Select Month',0,1,'',0,'');

	    var dateDropdown = document.getElementById('year_id');

	    var currentYear = new Date().getFullYear();
	    var earliestYear = 2000;

	    while (currentYear >= earliestYear) {
	        var dateOption = document.createElement('option');
	        dateOption.text = currentYear;
	        dateOption.value = currentYear;
	        dateDropdown.add(dateOption);
	        currentYear -= 1;
	    }

	</script>
@endsection