<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Add Gender Information</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="gender_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender_name">Gender Name</label>
                                    <input type="text" class="form-control" id="gender_name" name="gender_name" placeholder="Enter Gender Name" onkeyup="check_duplicate_value('gender_name','genders',this.value,0);" required>
                                    <div class="input-error" style="display:none; color: red;" id="gender_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender_code">Gender Code</label>
                                    <input type="text" class="form-control" id="gender_code" name="gender_code" placeholder="Enter Gender Code" onkeyup="check_duplicate_value('gender_code','genders',this.value,0);" required>
                                    <div class="input-error" style="display:none; color: red;" id="gender_code_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender_title">Gender Title</label>
                                    <input type="text" class="form-control" id="gender_title" name="gender_title" placeholder="Enter Gender Title" required>
                                    <div class="input-error" style="display:none; color: red;" id="gender_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="gender_deatils">Gender Details</label>
                                    <textarea class="form-control" id="gender_deatils" name="gender_deatils"></textarea>
                                    <div class="input-error" style="display:none; color: red;" id="gender_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="button" style="float:right" onclick="save_gender_info_data();" class="btn btn-primary">Add Gender Information</button>
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

        $('#gender_deatils').summernote({
            height: 64,
            focus: true
        })
    });

    function save_gender_info_data(){

        if( form_validation('gender_name*gender_code*gender_title','Gender Name*Gender Code*Gender Title')==false ){

            return false;
        }

        var gender_name = $("#gender_name").val();
        var gender_code = $("#gender_code").val();
        var gender_title = $("#gender_title").val();
        var gender_deatils = $("#gender_deatils").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        form_data.append("gender_name", gender_name);
        form_data.append("gender_code", gender_code);
        form_data.append("gender_title", gender_title);
        form_data.append("gender_deatils", gender_deatils);
        form_data.append("_token", token);

        http.open("POST","{{route('reference_data.gender.add')}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_gender_info_data_response;
    }

    function save_gender_info_data_response(){

        if(http.readyState == 4)
        {
            if(http.responseText=='Session Expire' || http.responseText=='Right Not Found'){

                alert('Session Expire');

                location.replace('<?php echo url('/login');?>');
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
                    $(".input-error").delay(3000).fadeOut(800);
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

                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                    $('input[name="_token"]').attr('value', data.csrf_token);
                }
            }

            document.getElementById("gender_form").reset();
        }
    }

</script>****{{csrf_token()}}