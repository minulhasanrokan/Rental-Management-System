<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Edit Salary Information</h3>
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
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="employee_id">Employye <span style="color:red;">*</span></label>
                                    <div id="employee_id_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="employee_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="salary">Salary <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control text_boxes_numeric" id="salary" name="salary" placeholder="Enter Salary" value="{{$salary_data->salary}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="salary_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="button" style="float:right" onclick="save_salary_info_data();" class="btn btn-primary">Update Salary Information</button>
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

    function save_salary_info_data(){

        if( form_validation('employee_id*salary','Employee*Salary')==false ){

            return false;
        }

        var employee_id = $("#employee_id").val();
        var salary = $("#salary").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        form_data.append("employee_id", employee_id);
        form_data.append("salary", salary);
        form_data.append("update_id", '{{$salary_data->salary_id}}');
        form_data.append("_token", token);

        freeze_window(0);

        http.open("POST","{{route('employee.salary.edit',$salary_data->salary_id)}}",true); 
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_salary_info_data_response;
    }

    function save_salary_info_data_response(){

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

    load_drop_down('users','id,name','employee_id','employee_id_container','Select Employee',0,1,'{{$salary_data->employee_id}}',0,'');

</script>****{{csrf_token()}}