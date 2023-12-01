<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Add Blood Group Information</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="blood_group_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="blood_group_name">Blood Group Name <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="blood_group_name" name="blood_group_name" placeholder="Enter Blood Group Name" onkeyup="check_duplicate_value('blood_group_name','blood_groups',this.value,0);" required>
                                    <div class="input-error" style="display:none; color: red;" id="blood_group_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="blood_group_code">Blood Group Code <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="blood_group_code" name="blood_group_code" placeholder="Enter Blood Group Code" onkeyup="check_duplicate_value('blood_group_code','blood_groups',this.value,0);" required>
                                    <div class="input-error" style="display:none; color: red;" id="blood_group_code_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="blood_group_title">Blood Group Title <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="blood_group_title" name="blood_group_title" placeholder="Enter Blood Group Title" required>
                                    <div class="input-error" style="display:none; color: red;" id="blood_group_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="blood_group_deatils">Blood Group Details</label>
                                    <textarea class="form-control" id="blood_group_deatils" name="blood_group_deatils"></textarea>
                                    <div class="input-error" style="display:none; color: red;" id="blood_group_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="button" style="float:right" onclick="save_blood_group_info_data();" class="btn btn-primary">Add Blood Group Information</button>
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

        $('#blood_group_deatils').summernote({
            height: 200,
            focus: true
        })
    });

    function save_blood_group_info_data(){

        if( form_validation('blood_group_name*blood_group_code*blood_group_title','Blood group Name*Blood group Code*Blood group Title')==false ){

            return false;
        }

        freeze_window(0);

        var blood_group_name = $("#blood_group_name").val();
        var blood_group_code = $("#blood_group_code").val();
        var blood_group_title = $("#blood_group_title").val();
        var blood_group_deatils = $("#blood_group_deatils").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        form_data.append("blood_group_name", blood_group_name);
        form_data.append("blood_group_code", blood_group_code);
        form_data.append("blood_group_title", blood_group_title);
        form_data.append("blood_group_deatils", blood_group_deatils);
        form_data.append("_token", token);

        http.open("POST","{{route('reference_data.blood_group.add')}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_blood_group_info_data_response;
    }

    function save_blood_group_info_data_response(){

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

                        document.getElementById("blood_group_form").reset();

                        $('#blood_group_deatils').summernote('reset');
                    }

                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                    $('input[name="_token"]').attr('value', data.csrf_token);
                }
            }
        }
    }

</script>****{{csrf_token()}}