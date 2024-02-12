@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                    <div class="card-header">
                        <h3 class="card-title">Add Cost Information</h3>
                    </div>
                    <div class="card-header" style="background-color: white;">
                        {!!$menu_data!!}
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="cost_form" method="post" autocomplete="off">
                        @csrf
                        <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cost_name">Cost Name <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="cost_name" name="cost_name" placeholder="Enter Cost Name" onkeyup="check_duplicate_value('cost_name','reference_costs',this.value,0);" required>
                                        <div class="input-error" style="display:none; color: red;" id="cost_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cost_code">Cost Code <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="cost_code" name="cost_code" placeholder="Enter Cost Code" onkeyup="check_duplicate_value('cost_code','reference_costs',this.value,0);" required>
                                        <div class="input-error" style="display:none; color: red;" id="cost_code_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cost_title">Cost Title <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="cost_title" name="cost_title" placeholder="Enter Cost Title" required>
                                        <div class="input-error" style="display:none; color: red;" id="cost_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cost_deatils">Cost Details</label>
                                        <textarea class="form-control" id="cost_deatils" name="cost_deatils"></textarea>
                                        <div class="input-error" style="display:none; color: red;" id="cost_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="button" style="float:right" onclick="save_cost_info_data();" class="btn btn-primary">Add Cost Information</button>
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

            $('#cost_deatils').summernote({
                height: 200,
                focus: true
            })
        });

        function save_cost_info_data(){

            if( form_validation('cost_name*cost_code*cost_title','Cost Name*Cost Code*Cost Title')==false ){

                return false;
            }

            var cost_name = $("#cost_name").val();
            var cost_code = $("#cost_code").val();
            var cost_title = $("#cost_title").val();
            var cost_deatils = $("#cost_deatils").val();

            var token = $('meta[name="csrf-token"]').attr('content');

            var form_data = new FormData();

            form_data.append("cost_name", cost_name);
            form_data.append("cost_code", cost_code);
            form_data.append("cost_title", cost_title);
            form_data.append("cost_deatils", cost_deatils);
            form_data.append("_token", token);

            freeze_window(0);

            http.open("POST","{{route('reference_data.cost.add')}}",true);
            http.setRequestHeader("X-CSRF-TOKEN",token);
            http.send(form_data);
            http.onreadystatechange = save_cost_info_data_response;
        }

        function save_cost_info_data_response(){

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

                            document.getElementById("cost_form").reset();

                            $('#cost_deatils').summernote('reset');
                        }
                    }

                    // hide all input error.............
                    $(".input-error").delay(3000).fadeOut(800);
                }
            }
        }
    </script>
@endsection