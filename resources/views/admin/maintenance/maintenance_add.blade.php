<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Add Maintenance Cost</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="cost_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cost_date">Cost Date <span style="color:red;">*</span></label>
                                    <input type="date" class="form-control" id="cost_date" name="cost_date" placeholder="Enter Cost Date" required>
                                    <div class="input-error" style="display:none; color: red;" id="entry_date_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
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
                                    <label for="reference_cost_id">Cost Reference <span style="color:red;">*</span></label>
                                    <div id="reference_cost_id_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="reference_cost_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cost">Cost <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control text_boxes_numeric" id="cost" name="cost" placeholder="Enter Cost" required>
                                    <div class="input-error" style="display:none; color: red;" id="cost_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="button" style="float:right" onclick="save_cost_data();" class="btn btn-primary">Add Maintenance Cost</button>
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

    function save_cost_data(){

        if( form_validation('cost_date*building_id*level_id*unit_id*reference_cost_id*cost','Cost Date*Building Name*Level Name*Unit Name*Unit Name*Cost Reference*Cost')==false ){

            return false;
        }

        var cost_date = $("#cost_date").val();
        var building_id = $("#building_id").val();
        var level_id = $("#level_id").val();
        var unit_id = $("#unit_id").val();
        var reference_cost_id = $("#reference_cost_id").val();
        var cost = $("#cost").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        form_data.append("cost_date", cost_date);
        form_data.append("building_id", building_id);
        form_data.append("level_id", level_id);
        form_data.append("reference_cost_id", reference_cost_id);
        form_data.append("unit_id", unit_id);
        form_data.append("cost", cost);

        form_data.append("_token", token);

        freeze_window(0);

        http.open("POST","{{route('maintenance.manage.add')}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_cost_data_response;
    }

    function save_cost_data_response(){

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

                    if(data.alert_type=='success'){

                        document.getElementById("cost_form").reset();
                    }
                }

                // hide all input error.............
                $(".input-error").delay(3000).fadeOut(800);
            }
        }
    }

    load_drop_down('buildings','id,building_name','building_id','building_id_container','Select Building',0,1,'',0,'onchange="load_drop_down_by_id(\'levels\',\'id,level_name\',\'level_id\',\'level_id_container\',\'Select Level\',0,1,\'\',0,this.value,\'building_id\',\'onchange=get_unit_load_drop_down_by_id(this.value)\',\'\',\'\')"');

    load_drop_down('reference_costs','id,cost_name','reference_cost_id','reference_cost_id_container','Select Cost Reference',0,1,'',0,'');

    function get_unit_load_drop_down_by_id(value)
    {

        load_drop_down_by_id('units','id,unit_name','unit_id','unit_id_container','Select Unit',0,1,'',0,value,'level_id','','','');
    }

</script>****{{csrf_token()}}