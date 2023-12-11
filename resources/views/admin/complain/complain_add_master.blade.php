@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                    <div class="card-header">
                        <h3 class="card-title">Add Complain Information</h3>
                    </div>
                    <div class="card-header" style="background-color: white;">
                        {!!$menu_data!!}
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="complain_form" method="post" autocomplete="off">
                        @csrf
                        <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="building_id">Building <span style="color:red;">*</span></label>
                                        <div id="building_id_container"></div>
                                        <div class="input-error" style="display:none; color: red;" id="building_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="level_id">Level <span style="color:red;">*</span></label>
                                        <div id="level_id_container">
                                            <select class="form-control select" style="width: 100%;" name="level_id" id="level_id">
                                                <option value="">Select Level</option>
                                            </select>
                                        </div>
                                        <div class="input-error" style="display:none; color: red;" id="level_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="unit_id">Unit <span style="color:red;">*</span></label>
                                        <div id="unit_id_container">
                                            <select class="form-control select" style="width: 100%; float: left;" name="unit_id" id="unit_id">
                                                <option value="">Select Unit</option>
                                            </select>
                                        </div>
                                        <div class="input-error" style="display:none; color: red;" id="unit_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="tenant_name">Tenant Name <span style="color:red;">*</span></label>
                                        <input type="text" readonly class="form-control" id="tenant_name" name="tenant_name" placeholder="Enter Tenant Name" required>
                                        <input type="hidden" readonly class="form-control" id="tenant_id" name="tenant_id" required>
                                        <div class="input-error" style="display:none; color: red;" id="tenant_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="complain_title">Complain Title <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="complain_title" name="complain_title" placeholder="Enter Complain Title" required>
                                        <div class="input-error" style="display:none; color: red;" id="complain_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="complain_details">Complain Details <span style="color:red;">*</span></label>
                                        <textarea class="form-control" id="complain_details" name="complain_details"></textarea>
                                        <div class="input-error" style="display:none; color: red;" id="complain_details_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="button" style="float:right" onclick="save_complain_info_data();" class="btn btn-primary">Add Complain Information</button>
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

            $('#complain_details').summernote({
                height: 150,
                focus: true
            })
        });

        function save_complain_info_data(){

            if( form_validation('building_id*level_id*unit_id*tenant_name*tenant_id*complain_title*complain_details','Building Name*Level Name*Unit Name*Tenant Name*Tenant Name*Complain Title*Complain Details')==false ){

                return false;
            }

            var building_id = $("#building_id").val();
            var level_id = $("#level_id").val();
            var unit_id = $("#unit_id").val();
            var tenant_name = $("#tenant_name").val();
            var tenant_id = $("#tenant_id").val();
            var complain_title = $("#complain_title").val();
            var complain_details = $("#complain_details").val();

            var token = $('meta[name="csrf-token"]').attr('content');

            var form_data = new FormData();
            
            form_data.append("building_id", building_id);
            form_data.append("level_id", level_id);
            form_data.append("unit_id", unit_id);
            form_data.append("tenant_name", tenant_name);
            form_data.append("tenant_id", tenant_id);
            form_data.append("complain_title", complain_title);
            form_data.append("complain_details", complain_details);

            form_data.append("_token", token);

            freeze_window(0);

            http.open("POST","{{route('complain_management.manage.add')}}",true);
            http.setRequestHeader("X-CSRF-TOKEN",token);
            http.send(form_data);
            http.onreadystatechange = save_complain_info_data_response;
        }

        function save_complain_info_data_response(){

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

                            document.getElementById("complain_form").reset();

                            $('#complain_details').summernote('reset');

                            $("#tenant_id").val('');
                            $("#tenant_name").val('');
                        }

                        $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                        $('input[name="_token"]').attr('value', data.csrf_token);
                    }
                }
            }
        }

        load_drop_down('buildings','id,building_name','building_id','building_id_container','Select Building',0,1,'',0,'onchange="load_drop_down_by_id(\'levels\',\'id,level_name\',\'level_id\',\'level_id_container\',\'Select Level\',0,1,\'\',0,this.value,\'building_id\',\'onchange=get_unit_load_drop_down_by_id(this.value)\',\'\',\'\')"');

        function get_unit_load_drop_down_by_id(value){

            load_drop_down_by_id('units','id,unit_name','unit_id','unit_id_container','Select Unit',0,1,'',0,value,'level_id',"onchange=\" get_tenant_data(this.value);\"",'rent_status','1');
        }

        function get_tenant_data(value){

            if(value=='' || value==0)
            {
                alert("Please Select Unit First");
                return false;
            }

            var data="&value="+value;

            if(value=='')
            {
                value =0;
            }
     
            http.open("GET","{{route('admin.get.current.tenant.info.by.unit')}}"+"/"+value,true);
            http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            http.send(data);
            http.onreadystatechange = get_tenant_data_reponse;
        }

        function get_tenant_data_reponse(){

            if(http.readyState == 4)
            {
                var reponse=trim(http.responseText).split("\*\*\*\*");

                if(reponse[0]=='Session Expire' || reponse[0]=='Right Not Found'){

                    alert(http.responseText);

                    location.replace('<?php echo url('/dashboard/logout');?>');
                }
                else{

                    var data = JSON.parse(reponse[0]);

                    if(data.length==0){

                        alert("No Tenant Information Found");
                        return false;
                    }
                    else{

                        $("#tenant_id").val(data[0]['tenant_id']);
                        $("#tenant_name").val(data[0]['tenant_name']);
                    }
                }
            }
        }

    </script>
@endsection