<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Add Unit Rent Information</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="unit_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="building_id">Building <span style="color:red;">*</span></label>
                                    <div id="building_id_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="building_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="unit_id">Unit <span style="color:red;">*</span></label>
                                    <div id="unit_id_container">
                                        <select class="form-control select" style="width: 100%;" name="unit_id" id="unit_id">
                                            <option value="">Select Unit</option>
                                        </select>
                                    </div>
                                    <div class="input-error" style="display:none; color: red;" id="unit_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="unit_rent">Rent <span style="color:red;">*</span></label>
                                    <input type="number" class="form-control" id="unit_rent" name="unit_rent" placeholder="Enter Rent" required>
                                    <div class="input-error" style="display:none; color: red;" id="unit_rent_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="water_bill">Water Bill <span style="color:red;">*</span></label>
                                    <input type="number" class="form-control" id="water_bill" name="water_bill" placeholder="Enter Water Bill" required>
                                    <div class="input-error" style="display:none; color: red;" id="water_bill_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="electricity_bill">Electricity Bill <span style="color:red;">*</span></label>
                                    <input type="number" class="form-control" id="electricity_bill" name="electricity_bill" placeholder="Enter Electricity Bill" required>
                                    <div class="input-error" style="display:none; color: red;" id="electricity_bill_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gas_bill">Gas Bill <span style="color:red;">*</span></label>
                                    <input type="number" class="form-control" id="gas_bill" name="gas_bill" placeholder="Enter Gas Bill" required>
                                    <div class="input-error" style="display:none; color: red;" id="gas_bill_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="security_bill">Security Bill <span style="color:red;">*</span></label>
                                    <input type="number" class="form-control" id="security_bill" name="security_bill" placeholder="Enter Security Bill" required>
                                    <div class="input-error" style="display:none; color: red;" id="security_bill_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="maintenance_bill">Maintenance Bill <span style="color:red;">*</span></label>
                                    <input type="number" class="form-control" id="maintenance_bill" name="maintenance_bill" placeholder="Enter Maintenance Bill" required>
                                    <div class="input-error" style="display:none; color: red;" id="maintenance_bill_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="service_bill">Service Charge <span style="color:red;">*</span></label>
                                    <input type="number" class="form-control" id="service_bill" name="service_bill" placeholder="Enter Service Charge" required>
                                    <div class="input-error" style="display:none; color: red;" id="service_bill_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="charity_bill">Charity Fund <span style="color:red;">*</span></label>
                                    <input type="number" class="form-control" id="charity_bill" name="charity_bill" placeholder="Enter Charity Fund" required>
                                    <div class="input-error" style="display:none; color: red;" id="charity_bill_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="other_bill">Other Bill <span style="color:red;">*</span></label>
                                    <input type="number" class="form-control" id="other_bill" name="other_bill" placeholder="Enter Other Bill" required>
                                    <div class="input-error" style="display:none; color: red;" id="other_bill_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="button" style="float:right" onclick="save_unit_info_data();" class="btn btn-primary">Add Unit Bill Information</button>
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

        $('#unit_deatils').summernote({
            height: 60,
            focus: true
        })
    });

    function save_unit_info_data(){

        if( form_validation('building_id*level_id*unit_name*unit_code*unit_title*unit_size*unit_photo','Unit Building*Unit Level*Unit Name*Unit Code*Unit Title* Unit Size*Unit Image')==false ){

            return false;
        }

        var unit_name = $("#unit_name").val();
        var building_id = $("#building_id").val();
        var level_id = $("#level_id").val();
        var unit_code = $("#unit_code").val();
        var unit_title = $("#unit_title").val();
        var unit_size = $("#unit_size").val();
        var unit_address = $("#unit_address").val();
        var unit_deatils = $("#unit_deatils").val();
        var hidden_unit_photo = $("#hidden_unit_photo").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        var unit_photo = $('#unit_photo')[0].files;
        form_data.append('unit_photo',unit_photo[0]);

        form_data.append("building_id", building_id);
        form_data.append("level_id", level_id);
        form_data.append("unit_name", unit_name);
        form_data.append("unit_code", unit_code);
        form_data.append("unit_title", unit_title);
        form_data.append("unit_size", unit_size);
        form_data.append("unit_address", unit_address);
        form_data.append("unit_deatils", unit_deatils);
        form_data.append("hidden_unit_photo", hidden_unit_photo);
        form_data.append("_token", token);

        http.open("POST","{{route('floor_management.unit.add')}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_unit_info_data_response;
    }

    function save_unit_info_data_response(){

        if(http.readyState == 4)
        {
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

                        document.getElementById("unit_form").reset();

                        $('#unit_deatils').summernote('reset');

                        $("#unit_logo_photo").attr("src","{{asset('uploads/unit/unit_logo.png')}}");
                        $("#unit_photo_photo").attr("src","{{asset('uploads/unit/unit_logo.png')}}");
                    }

                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                    $('input[name="_token"]').attr('value', data.csrf_token);
                }
            }
        }
    }


    load_drop_down('buildings','id,building_name','building_id','building_id_container','Select Building',0,1,'',0,'onchange="load_drop_down_by_id(\'levels\',\'id,level_name\',\'level_id\',\'level_id_container\',\'Select Level\',0,1,\'\',0,this.value,\'building_id\',\'onchange=get_unit_load_drop_down_by_id(this.value)\')"');

    function get_unit_load_drop_down_by_id(value)
    {
        load_drop_down('units','id,unit_name','unit_id','unit_id_container','Select Unit',0,1,'',0,'');

        load_drop_down_by_id('units','id,unit_name','unit_id','unit_id_container','Select Unit',0,1,'',0,value,'level_id','');
    }

</script>****{{csrf_token()}}