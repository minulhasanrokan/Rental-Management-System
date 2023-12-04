@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 20px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">SMS API Information</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="system_deatils_form" method="post" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="api_token">SMS API Token <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="api_token" name="api_token" placeholder="Enter API Token" value="{{$sms_data['api_token']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="api_token_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="api_url">SMS API Url <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="api_url" name="api_url" placeholder="Enter SAPI Url" value="{{$sms_data['api_url']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="api_url_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="api_mobile">SMS API Mobile <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="api_mobile" name="api_mobile" placeholder="Enter API Mobile" value="{{$sms_data['api_mobile']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="api_mobile_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="api_username">SMS API User Name <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="api_username" name="api_username" placeholder="Enter API User Name" value="{{$sms_data['api_username']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="api_username_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="api_password">SMS API User Password <span style="color:red;">*</span></label>
                                        <input type="password" class="form-control" id="api_password" name="api_password" placeholder="Enter API Password" value="{{$sms_data['api_password']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="api_password_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="system_url">SMS API System Url <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="system_url" name="system_url" placeholder="Enter System Url" value="{{$sms_data['system_url']}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="system_url_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="button" style="float:right" onclick="save_system_sms_data();" class="btn btn-primary">Update SMS API Information</button>
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

        function save_system_sms_data(){

            if( form_validation('api_token*api_url*api_mobile*api_username*api_password*system_url','SMS API Token*SMS API Url*SMS API Mobile*SMS API User Name*SMS API Password*System Url')==false ){

                return false;
            }

            var api_token = $("#api_token").val();
            var api_url = $("#api_url").val();
            var api_mobile = $("#api_mobile").val();
            var api_username = $("#api_username").val();
            var api_password = $("#api_password").val();
            var system_url = $("#system_url").val();

            var token = $('meta[name="csrf-token"]').attr('content');

            var form_data = new FormData();

            form_data.append("api_token", api_token);
            form_data.append("api_url", api_url);
            form_data.append("api_mobile", api_mobile);
            form_data.append("api_username", api_username);
            form_data.append("api_password", api_password);
            form_data.append("system_url", system_url);
            form_data.append("_token", token);

            freeze_window(0);

            http.open("POST","{{route('system_setting.sms.add')}}",true);
            http.setRequestHeader("X-CSRF-TOKEN",token);
            http.send(form_data);
            http.onreadystatechange = save_system_sms_data_response;

        }

        function save_system_sms_data_response(){

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

    </script>
@endsection