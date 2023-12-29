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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="normal_user_type">Normal User Type <span style="color:red;">*</span></label>
                                    <div id="normal_user_type_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="normal_user_type_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="owner_user_type">Owner User Type <span style="color:red;">*</span></label>
                                    <div id="owner_user_type_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="owner_user_type_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tenant_user_type">Tenant User Type <span style="color:red;">*</span></label>
                                    <div id="tenant_user_type_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="tenant_user_type_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                             <div class="col-md-3">
                                <div class="form-group">
                                    <label for="employee_user_type">Employee User Type <span style="color:red;">*</span></label>
                                    <div id="employee_user_type_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="employee_user_type_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="normal_user_group">Normal User Group <span style="color:red;">*</span></label>
                                    <div id="normal_user_group_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="normal_user_group_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="owner_user_group">Owner User Group <span style="color:red;">*</span></label>
                                    <div id="owner_user_group_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="owner_user_group_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tenant_user_group">Tenant User Group <span style="color:red;">*</span></label>
                                    <div id="tenant_user_group_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="tenant_user_group_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="employee_user_group">Employee User Group <span style="color:red;">*</span></label>
                                    <div id="employee_user_group_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="employee_user_group_error" style="display: inline-block; width:100%; color: red;"></div>
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

        if( form_validation('normal_user_type*owner_user_type*tenant_user_type*employee_user_type*normal_user_group*owner_user_group*tenant_user_group*employee_user_group','Normal User Type*Owner User Type*Tenant User Type*Employee User Type*Normal User Group*Owner User Group*Tenant User Group*Employee User Group')==false ){

            return false;
        }

        var normal_user_type = $("#normal_user_type").val();
        var owner_user_type = $("#owner_user_type").val();
        var tenant_user_type = $("#tenant_user_type").val();
        var employee_user_type = $("#employee_user_type").val();
        var tenant_user_group = $("#tenant_user_group").val();
        var normal_user_group = $("#normal_user_group").val();
        var owner_user_group = $("#owner_user_group").val();
        var employee_user_group = $("#employee_user_group").val();

        if(normal_user_type==owner_user_type){

            alert("Normal User Type And Owner User Type Can Not Be Same");

            $("#owner_user_type").val('');

            return false;
        }

        if(normal_user_type==tenant_user_type){

            alert("Normal User Type And Tenant User Type Can Not Be Same");

            $("#tenant_user_type").val('');

            return false;
        }

        if(normal_user_type==employee_user_type){

            alert("Normal User Type And Employee User Type Can Not Be Same");

            $("#employee_user_type").val('');

            return false;
        }

        if(owner_user_type==tenant_user_type){

            alert("Owner User Type And Tenant User Type Can Not Be Same");

            $("#tenant_user_type").val('');

            return false;
        }

        if(owner_user_type==employee_user_type){

            alert("Owner User Type And Employee User Type Can Not Be Same");

            $("#employee_user_type").val('');

            return false;
        }

        if(employee_user_type==tenant_user_type){

            alert("Employee User Type And Tenant User Type Can Not Be Same");

            $("#tenant_user_type").val('');
            
            return false;
        }

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        form_data.append("normal_user_type", normal_user_type);
        form_data.append("owner_user_type", owner_user_type);
        form_data.append("tenant_user_type", tenant_user_type);
        form_data.append("employee_user_type", employee_user_type);
        form_data.append("tenant_user_group", tenant_user_group);
        form_data.append("normal_user_group", normal_user_group);
        form_data.append("owner_user_group", owner_user_group);
        form_data.append("employee_user_group", employee_user_group);

        form_data.append("_token", token);

        freeze_window(0);

        http.open("POST","{{route('system_setting.user_config.add')}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_system_info_data_response;

    }

    function save_system_info_data_response(){

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
                }

                // hide all input error.............
                $(".input-error").delay(3000).fadeOut(800);
            }
        }
    }

    load_drop_down('user_types','id,user_type_name','normal_user_type','normal_user_type_container','Select Normal User Type',0,1,'{{$user_config_data['normal_user_type']}}',0,'');
    load_drop_down('user_types','id,user_type_name','owner_user_type','owner_user_type_container','Select Owner User Type',0,1,'{{$user_config_data['owner_user_type']}}',0,'');
    load_drop_down('user_types','id,user_type_name','tenant_user_type','tenant_user_type_container','Select Tenant User Type',0,1,'{{$user_config_data['tenant_user_type']}}',0,'');
    load_drop_down('user_types','id,user_type_name','employee_user_type','employee_user_type_container','Select Employee User Type',0,1,'{{$user_config_data['employee_user_type']}}',0,'');
    load_drop_down('user_groups','id,group_name','tenant_user_group','tenant_user_group_container','Select Tenant User Group',0,1,'{{$user_config_data['tenant_user_group']}}',0,'');
    load_drop_down('user_groups','id,group_name','normal_user_group','normal_user_group_container','Select Normal User Group',0,1,'{{$user_config_data['normal_user_group']}}',0,'');
    load_drop_down('user_groups','id,group_name','owner_user_group','owner_user_group_container','Select Owner User Group',0,1,'{{$user_config_data['owner_user_group']}}',0,'');
    load_drop_down('user_groups','id,group_name','employee_user_group','employee_user_group_container','Select Tenant User Group',0,1,'{{$user_config_data['employee_user_group']}}',0,'');

</script>****{{csrf_token()}}