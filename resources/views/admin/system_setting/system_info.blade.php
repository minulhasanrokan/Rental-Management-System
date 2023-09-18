<div class="container-fluid" style="padding-top: 20px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">System Information</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="system_deatils_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="system_name">System Name <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="system_name" name="system_name" placeholder="Enter System Name" value="{{$system_data['system_name']}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="system_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="system_email">System E-mail <span style="color:red;">*</span></label>
                                    <input type="email" class="form-control" id="system_email" name="system_email" placeholder="Enter System E-mail" value="{{$system_data['system_email']}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="system_email_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="system_mobile">System Mobile Number <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="system_mobile" name="system_mobile" placeholder="Enter System Mobile Number" value="{{$system_data['system_mobile']}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="system_mobile_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="system_title">System Title <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="system_title" name="system_title" placeholder="Enter System Title" value="{{$system_data['system_title']}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="system_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="system_address">System Address <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="system_address" name="system_address" placeholder="Enter System Address" value="{{$system_data['system_address']}}">
                                    <div class="input-error" style="display:none; color: red;" id="system_address_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="system_copy_right">System Copy Right Text <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="system_copy_right" name="system_copy_right" placeholder="Enter System Copy Right Text" value="{{$system_data['system_copy_right']}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="system_copy_right_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="system_deatils">System Details</label>
                                    <textarea class="form-control" id="system_deatils" name="system_deatils">{{$system_data['system_deatils']}}</textarea>
                                    <div class="input-error" style="display:none; color: red;" id="system_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="system_logo">System Logo <span style="color:red;">*</span></label>
                                    <input onchange="readUrl(this,'system_logo_photo');" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" id="system_logo" name="system_logo" placeholder="Enter System Logo" required>
                                    <input type="hidden" name="hidden_system_logo" id="hidden_system_logo" value="{{$system_data['system_logo']}}">
                                    <div class="input-error" style="display:none; color: red;" id="system_logo_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <img class="rounded avatar-lg" width="80" height="80" id="system_logo_photo" src="{{asset('uploads/logo')}}/{{!empty(($system_data['system_logo']))? $system_data['system_logo'] : 'rental_logo.png'}}"/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="system_bg_image">System Backgroup <span style="color:red;">*</span></label>
                                    <input onchange="readUrl(this,'system_bg_image_photo');" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" id="system_bg_image" name="system_bg_image" placeholder="Enter System Backgroup" required>
                                    <input type="hidden" name="hidden_system_bg_image" id="hidden_system_bg_image" value="{{$system_data['system_bg_image']}}">
                                    <div class="input-error" style="display:none; color: red;" id="system_logo_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <img class="rounded avatar-lg" width="80" height="80" id="system_bg_image_photo" src="{{asset('uploads/login_bg')}}/{{!empty(($system_data['system_bg_image']))? $system_data['system_bg_image'] : 'login_register_bg.jpg'}}"/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="system_favicon">System Favicon <span style="color:red;">*</span></label>
                                    <input onchange="readUrl(this,'system_favicon_photo');" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" id="system_favicon" name="system_favicon" placeholder="Enter System Favicon" required>
                                    <input type="hidden" name="hidden_system_favicon" id="hidden_system_favicon" value="{{$system_data['system_favicon']}}">
                                    <div class="input-error" style="display:none; color: red;" id="system_favicon_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <img class="rounded avatar-lg" width="80" height="80" id="system_favicon_photo" src="{{asset('uploads/icon')}}/{{!empty(($system_data['system_favicon']))? $system_data['system_favicon'] : 'rental_icon.png'}}"/>
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

        $('#system_deatils').summernote({
            height: 64,
            focus: true
        })
    });

    function save_system_info_data(){

        if( form_validation('system_name*system_email*system_mobile*system_title*system_address*system_copy_right','System Name*System E-mail*System Mobile*System Title*System Address*Copyright Text')==false ){

            return false;
        }

        var mobile_number = $("#system_mobile").val();

        var status = check_mobile_number(mobile_number);

        if(status==false){

            alert("Please Input Valied Mobile Number");

            $("#system_mobile").focus();

            return false;
        }

        var hidden_system_logo = $("#hidden_system_logo").val();
        var hidden_system_bg_image = $("#hidden_system_bg_image").val();
        var hidden_system_favicon = $("#hidden_system_favicon").val();

        if(hidden_system_logo==''){

            if( form_validation('system_logo','System Logo')==false ){
                return false;
            }
        }

        if(hidden_system_bg_image==''){

            if( form_validation('system_bg_image','System Background')==false ){
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

                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                    $('input[name="_token"]').attr('value', data.csrf_token);
                }
            }
        }
    }

</script>****{{csrf_token()}}