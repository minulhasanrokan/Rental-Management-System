@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                    <div class="card-header">
                        <h3 class="card-title">Add Department Information</h3>
                    </div>
                    <div class="card-header" style="background-color: white;">
                        {!!$menu_data!!}
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="department_form" method="post" autocomplete="off">
                        @csrf
                        <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="department_name">Department Name <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="department_name" name="department_name" placeholder="Enter Department Name" onkeyup="check_duplicate_value('department_name','departments',this.value,0);" required>
                                        <div class="input-error" style="display:none; color: red;" id="department_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="department_code">Department Code <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="department_code" name="department_code" placeholder="Enter Department Code" onkeyup="check_duplicate_value('department_code','departments',this.value,0);" required>
                                        <div class="input-error" style="display:none; color: red;" id="department_code_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="department_title">Department Title <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="department_title" name="department_title" placeholder="Enter Department Title" required>
                                        <div class="input-error" style="display:none; color: red;" id="department_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="department_deatils">Department Details</label>
                                        <textarea class="form-control" id="department_deatils" name="department_deatils"></textarea>
                                        <div class="input-error" style="display:none; color: red;" id="department_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="button" style="float:right" onclick="save_department_info_data();" class="btn btn-primary">Add Department Information</button>
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

            $('#department_deatils').summernote({
                height: 200,
                focus: true
            })
        });

        function save_department_info_data(){

            if( form_validation('department_name*department_code*department_title','Department Name*Department Code*Department Title')==false ){

                return false;
            }

            var department_name = $("#department_name").val();
            var department_code = $("#department_code").val();
            var department_title = $("#department_title").val();
            var department_deatils = $("#department_deatils").val();

            var token = $('meta[name="csrf-token"]').attr('content');

            var form_data = new FormData();

            form_data.append("department_name", department_name);
            form_data.append("department_code", department_code);
            form_data.append("department_title", department_title);
            form_data.append("department_deatils", department_deatils);
            form_data.append("_token", token);

            freeze_window(0);

            http.open("POST","{{route('reference_data.department.add')}}",true);
            http.setRequestHeader("X-CSRF-TOKEN",token);
            http.send(form_data);
            http.onreadystatechange = save_department_info_data_response;
        }

        function save_department_info_data_response(){

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

                            document.getElementById("department_form").reset();

                            $('#department_deatils').summernote('reset');
                        }

                        $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                        $('input[name="_token"]').attr('value', data.csrf_token);
                    }
                }
            }
        }

    </script>
@endsection