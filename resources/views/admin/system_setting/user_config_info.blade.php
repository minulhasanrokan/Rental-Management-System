<div class="container-fluid" style="padding-top: 20px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">User Config Information</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="system_deatils_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="normal_user_type">Normal User Type <span style="color:red;">*</span></label>
                                    <div id="normal_user_type_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="normal_user_type_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="owner_user_type">Owner User Type <span style="color:red;">*</span></label>
                                    <div id="owner_user_type_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="owner_user_type_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tenant_user_type">Tenant User Type <span style="color:red;">*</span></label>
                                    <div id="tenant_user_type_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="tenant_user_type_error" style="display: inline-block; width:100%; color: red;"></div>
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
    
    function save_system_info_data(){

        if( form_validation('normal_user_type*owner_user_type*tenant_user_type','Normal User Type*Owner User Type*Tenant User Type')==false ){

            return false;
        }

        var normal_user_type = $("#normal_user_type").val();
        var owner_user_type = $("#owner_user_type").val();
        var tenant_user_type = $("#tenant_user_type").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        form_data.append("normal_user_type", normal_user_type);
        form_data.append("owner_user_type", owner_user_type);
        form_data.append("tenant_user_type", tenant_user_type);

        form_data.append("_token", token);

        http.open("POST","{{route('system_setting.user_config.add')}}",true);
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

    load_drop_down('user_types','id,user_type_name','normal_user_type','normal_user_type_container','Select Normal User Type',0,1,'{{$user_config_data['normal_user_type']}}');
    load_drop_down('user_types','id,user_type_name','owner_user_type','owner_user_type_container','Select Owner User Type',0,1,'{{$user_config_data['owner_user_type']}}');
    load_drop_down('user_types','id,user_type_name','tenant_user_type','tenant_user_type_container','Tenant Normal User Type',0,1,'{{$user_config_data['tenant_user_type']}}');

</script>****{{csrf_token()}}