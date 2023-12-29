@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                    <div class="card-header">
                        <h3 class="card-title">Chnage Password</h3>
                    </div>
                    <div class="card-header" style="background-color: white;">
                        {!!$menu_data!!}
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="update_password_form" method="post" autocomplete="off">
                        @csrf
                        <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="old_password">Old Password <span style="color:red;">*</span></label>
                                        <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter Old Password" required>
                                        <div class="input-error" style="display:none; color: red;" id="old_password_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password">New Password <span style="color:red;">*</span></label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password" required>
                                        <div class="input-error" style="display:none; color: red;" id="password_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm New password <span style="color:red;">*</span></label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Retype New Password" required>
                                        <div class="input-error" style="display:none; color: red;" id="confirm_password_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="button" style="float:right" onclick="update_password_data();" class="btn btn-primary">Update Password</button>
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

        function update_password_data(){

            if( form_validation('old_password*password*confirm_password','Old Password*New Password*Confirm New password')==false ){

                return false;
            }

            var old_password = $("#old_password").val();
            var password = $("#password").val();
            var confirm_password = $("#confirm_password").val();

            var token = $('meta[name="csrf-token"]').attr('content');

            var form_data = new FormData();

            form_data.append("old_password", old_password);
            form_data.append("password", password);
            form_data.append("confirm_password", confirm_password);
            form_data.append("_token", token);

            freeze_window(0);

            http.open("POST","{{route('profile.manage.change_password')}}",true);
            http.setRequestHeader("X-CSRF-TOKEN",token);
            http.send(form_data);
            http.onreadystatechange = update_password_data_response;
        }

        function update_password_data_response(){

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

                            if (!confirm("Do You Want To Stay Login")){
                                
                                location.replace('<?php echo url('/dashboard/logout');?>');
                            }

                            document.getElementById("update_password_form").reset();
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