<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Add Designation Information</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="designation_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="designation_name">Designation Name <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="designation_name" name="designation_name" placeholder="Enter Designation Name" onkeyup="check_duplicate_value('designation_name','designations',this.value,0);" required>
                                    <div class="input-error" style="display:none; color: red;" id="designation_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="designation_code">Designation Code <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="designation_code" name="designation_code" placeholder="Enter Designation Code" onkeyup="check_duplicate_value('designation_code','designations',this.value,0);" required>
                                    <div class="input-error" style="display:none; color: red;" id="designation_code_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="designation_title">Designation Title <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="designation_title" name="designation_title" placeholder="Enter Designation Title" required>
                                    <div class="input-error" style="display:none; color: red;" id="designation_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="designation_deatils">Designation Details</label>
                                    <textarea class="form-control" id="designation_deatils" name="designation_deatils"></textarea>
                                    <div class="input-error" style="display:none; color: red;" id="designation_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="button" style="float:right" onclick="save_designation_info_data();" class="btn btn-primary">Add Designation Information</button>
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

        $('#designation_deatils').summernote({
            height: 200,
            focus: true
        })
    });

    function save_designation_info_data(){

        if( form_validation('designation_name*designation_code*designation_title','Designation Name*Designation Code*Designation Title')==false ){

            return false;
        }

        var designation_name = $("#designation_name").val();
        var designation_code = $("#designation_code").val();
        var designation_title = $("#designation_title").val();
        var designation_deatils = $("#designation_deatils").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        form_data.append("designation_name", designation_name);
        form_data.append("designation_code", designation_code);
        form_data.append("designation_title", designation_title);
        form_data.append("designation_deatils", designation_deatils);
        form_data.append("_token", token);

        http.open("POST","{{route('reference_data.designation.add')}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_designation_info_data_response;
    }

    function save_designation_info_data_response(){

        if(http.readyState == 4)
        {
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

                        document.getElementById("designation_form").reset();

                        $('#designation_deatils').summernote('reset');
                    }

                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                    $('input[name="_token"]').attr('value', data.csrf_token);
                }
            }
        }
    }

</script>****{{csrf_token()}}