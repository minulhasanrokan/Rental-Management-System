<div class="container-fluid" style="padding-top: 20px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add User Group Information</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="user_group_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="group_name">User Group Name</label>
                                    <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter User Group Name" value="" required>
                                    <div class="input-error" style="display:none; color: red;" id="group_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="group_code">User Group Code</label>
                                    <input type="email" class="form-control" id="group_code" name="group_code" placeholder="Enter User Group Code" value="" required>
                                    <div class="input-error" style="display:none; color: red;" id="group_code_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="group_title">User Group Title</label>
                                    <input type="text" class="form-control" id="group_title" name="group_title" placeholder="Enter User Group Title" value="" required>
                                    <div class="input-error" style="display:none; color: red;" id="group_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="group_deatils">User Group Details</label>
                                    <textarea class="form-control" id="group_deatils" name="group_deatils"></textarea>
                                    <div class="input-error" style="display:none; color: red;" id="group_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="group_logo">User Group Photo</label>
                                    <input onchange="readUrl(this,'group_logo_photo');" type="file" class="form-control" id="group_logo" name="group_logo" placeholder="Enter System Photo" required>
                                    <input type="hidden" name="hidden_group_logo" id="hidden_group_logo" value="">
                                    <div class="input-error" style="display:none; color: red;" id="group_logo_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <img class="rounded avatar-lg" width="80" height="80" id="group_logo_photo" src="{{asset('uploads/user_group/user_group.png')}}"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="group_icon">User Group Icon</label>
                                    <input onchange="readUrl(this,'group_icon_photo');" type="file" class="form-control" id="group_icon" name="group_icon" placeholder="Enter User Group Icon" required>
                                    <input type="hidden" name="hidden_group_icon" id="hidden_group_icon" value="">
                                    <div class="input-error" style="display:none; color: red;" id="group_iconn_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <img class="rounded avatar-lg" width="80" height="80" id="group_icon_photo" src="{{asset('uploads/user_group/user_group_icon.png')}}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="button" style="float:right" onclick="save_system_info_data();" class="btn btn-primary">Update System Information</button>
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

        $('#group_deatils').summernote({
            height: 64,
            focus: true
        })
    });

    function readUrl(input,view_id){
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e){
                $('#'+view_id).attr('src', e.target.result).width(80).height(80);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function save_system_info_data(){

        if( form_validation('system_name*system_email*system_mobile*system_title*system_copy_right','System Name*System E-mail*System Mobile*System Title*Copyright Text')==false ){

            return false;
        }

        var hidden_system_logo = $("#hidden_system_logo").val();
        var hidden_system_favicon = $("#hidden_system_favicon").val();

        if(hidden_system_logo==''){

            if( form_validation('system_logo','System Logo')==false ){
                return false;
            }
        }

        if(hidden_system_favicon==''){

            if( form_validation('system_favicon','System Favicon')==false ){
                return false;
            }
        }

        var system_name = $("#system_name").val();
        var system_email = $("#system_email").val();
        var system_mobile = $("#system_mobile").val();
        var system_title = $("#system_title").val();
        var system_address = $("#system_address").val();
        var system_copy_right = $("#system_copy_right").val();
        var system_deatils = $("#system_deatils").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        var system_logo = $('#system_logo')[0].files;
        var system_bg_image = $('#system_bg_image')[0].files;
        var system_favicon = $('#system_favicon')[0].files;

        form_data.append('system_logo',system_logo[0]);
        form_data.append('system_bg_image',system_bg_image[0]);
        form_data.append('system_favicon',system_favicon[0]);

        form_data.append("system_name", system_name);
        form_data.append("system_email", system_email);
        form_data.append("system_mobile", system_mobile);
        form_data.append("system_title", system_title);
        form_data.append("system_address", system_address);
        form_data.append("system_copy_right", system_copy_right);
        form_data.append("system_deatils", system_deatils);
        form_data.append("hidden_system_logo", hidden_system_logo);
        form_data.append("hidden_system_favicon", hidden_system_favicon);
        form_data.append("_token", token);

        http.open("POST","{{route('system_setting.information.add')}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_system_info_data_response;

    }

    function save_system_info_data_response(){

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
                }
            }
        }
    }

</script>****{{csrf_token()}}