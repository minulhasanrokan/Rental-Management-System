@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 20px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">System E-mail Information</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="system_deatils_form" method="post" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email_id">System E-mail Address <span style="color:red;">*</span></label>
                                        <input type="email" class="form-control" id="email_id" name="email_id" placeholder="Enter System E-mail Address" value="{{$mail_data['email_id']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="email_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email_host">System E-mail Host <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="email_host" name="email_host" placeholder="Enter System E-mail Host" value="{{$mail_data['email_host']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="email_host_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email_mailer">System E-mail Mailer <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="email_mailer" name="email_mailer" placeholder="Enter System E-mail Mailer" value="{{$mail_data['email_mailer']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="email_mailer_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email_port">System E-mail Port <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="email_port" name="email_port" placeholder="Enter System E-mail Port" value="{{$mail_data['email_port']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="email_port_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email_username">System E-mail User Name <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="email_username" name="email_username" placeholder="Enter System E-mail User Name" value="{{$mail_data['email_username']}}">
                                        <div class="input-error" style="display:none; color: red;" id="email_username_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email_password">System E-mail Password <span style="color:red;">*</span></label>
                                        <input type="password" class="form-control" id="email_password" name="email_password" placeholder="Enter System E-mail Password" value="{{$mail_data['email_password']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="email_password_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email_encription">System E-mail Encription</label>
                                        <input type="text" class="form-control" id="email_encription" name="email_encription" placeholder="Enter System E-mail Encription" value="{{$mail_data['email_encription']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="email_encription_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email_name">System E-mail Name <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="email_name" name="email_name" placeholder="Enter System E-mail Name" value="{{$mail_data['email_name']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="email_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email_driver">System E-mail Driver</label>
                                        <input type="text" class="form-control" id="email_driver" name="email_driver" placeholder="Enter System E-mail Driver" value="{{$mail_data['email_driver']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="email_driver_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="system_url">System Url</label>
                                        <input type="text" class="form-control" id="system_url" name="system_url" placeholder="Enter System Url" value="{{$mail_data['system_url']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="system_url_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="button" style="float:right" onclick="save_system_mail_data();" class="btn btn-primary">Update System E-mail Information</button>
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

        function save_system_mail_data(){

            if( form_validation('email_id*email_host*email_mailer*email_port*email_username*email_password*email_name','System E-mail Address*System E-mail Host*System E-mail Mailer*System E-mail Port*System E-mail User Name*System E-mail Password*System E-mail Name')==false ){

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

            var email_id = $("#email_id").val();
            var email_host = $("#email_host").val();
            var email_mailer = $("#email_mailer").val();
            var email_port = $("#email_port").val();
            var email_username = $("#email_username").val();
            var email_password = $("#email_password").val();
            var email_encription = $("#email_encription").val();
            var email_name = $("#email_name").val();
            var email_driver = $("#email_driver").val();
            var system_url = $("#system_url").val();

            var token = $('meta[name="csrf-token"]').attr('content');

            var form_data = new FormData();

            form_data.append("email_id", email_id);
            form_data.append("email_host", email_host);
            form_data.append("email_mailer", email_mailer);
            form_data.append("email_port", email_port);
            form_data.append("email_username", email_username);
            form_data.append("email_password", email_password);
            form_data.append("email_encription", email_encription);
            form_data.append("email_name", email_name);
            form_data.append("email_driver", email_driver);
            form_data.append("system_url", system_url);
            form_data.append("_token", token);

            freeze_window(0);

            http.open("POST","{{route('system_setting.mail.add')}}",true);
            http.setRequestHeader("X-CSRF-TOKEN",token);
            http.send(form_data);
            http.onreadystatechange = save_system_mail_data_response;

        }

        function save_system_mail_data_response(){

            if(http.readyState == 4)
            {
                release_freezing();

                if(http.responseText=='Session Expire' || http.responseText=='Right Not Found'){

                    alert(http.responseText);

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

                    // hide all input error.............
                    $(".input-error").delay(3000).fadeOut(800);
                }
            }
        }

    </script>
@endsection