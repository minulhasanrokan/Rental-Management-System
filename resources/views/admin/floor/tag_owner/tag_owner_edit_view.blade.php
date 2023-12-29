@php
    
    $user_type = $user_config_data['owner_user_type'];

    $owner_data = DB::table('users')
        ->where('users.status',1)
        ->where('users.user_type',$user_type)
        ->where('users.delete_status',0)
        ->orWhere('users.id',$tag_owner_data->owner_id)
        ->orderBy('users.name', 'ASC')
        ->get()->toArray();

@endphp
<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Edit Tag Unit Owner Information</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="unit_rent_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="owner_id">Owner Name <span style="color:red;">*</span></label>
                                    <div id="owner_id_container">
                                        <select class="form-control select" style="width: 100%;" name="owner_id" id="owner_id">
                                            <option value="">Select Owner</option>
                                            @foreach($owner_data as $data)
                                            <option value="{{$data->id}}">{{$data->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-error" style="display:none; color: red;" id="owner_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
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
                                        <select class="form-control select" style="width: 100%;" name="unit_id" id="unit_id">
                                            <option value="">Select Unit</option>
                                        </select>
                                    </div>
                                    <div class="input-error" style="display:none; color: red;" id="unit_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @foreach($user_right_data as $data)
                            <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$encrypt_id}}','');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
                        @endforeach
                        <button type="button" style="float:right" onclick="save_unit_rent_info_data();" class="btn btn-primary">Update Tag Unit Owner</button>
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

    $("#owner_id").val('{{$tag_owner_data->owner_id}}');

    function save_unit_rent_info_data(){

        if( form_validation('owner_id*building_id*level_id*unit_id','Owner Name*Building Name*Level Name*Unit Name')==false ){

            return false;
        }

        var owner_id = $("#owner_id").val();
        var building_id = $("#building_id").val();
        var level_id = $("#level_id").val();
        var unit_id = $("#unit_id").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        form_data.append("owner_id", owner_id);
        form_data.append("building_id", building_id);
        form_data.append("level_id", level_id);
        form_data.append("unit_id", unit_id);
        form_data.append("update_id", '{{$tag_owner_data->id}}');
        form_data.append("_token", token);

        freeze_window(0);

        http.open("POST","{{route('floor_management.tag_unit_owner.edit',$tag_owner_data->id)}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_unit_rent_info_data_response;
    }

    function save_unit_rent_info_data_response(){

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

    load_drop_down('buildings','id,building_name','building_id','building_id_container','Select Building',0,1,'{{$tag_owner_data->building_id}}',0,'onchange="load_drop_down_by_id(\'levels\',\'id,level_name\',\'level_id\',\'level_id_container\',\'Select Level\',0,1,\'\',0,this.value,\'building_id\',\'onchange=get_unit_load_drop_down_by_id(this.value)\',\'\',\'\')"');

    load_drop_down_by_id('levels','id,level_name','level_id','level_id_container','Select Level',0,1,'{{$tag_owner_data->level_id}}',0,'{{$tag_owner_data->building_id}}','building_id','','','');

    load_drop_down_by_id('units','id,unit_name','unit_id','unit_id_container','Select Unit',0,1,'{{$tag_owner_data->unit_id}}',0,'{{$tag_owner_data->level_id}}','level_id',"onchange=\"check_duplicate_value('unit_id','tag_owners',this.value,'{{$tag_owner_data->id}}');\"",'','');

    function get_unit_load_drop_down_by_id(value)
    {

        load_drop_down_by_id('units','id,unit_name','unit_id','unit_id_container','Select Unit',0,1,'',0,value,'level_id',"onchange=\"check_duplicate_value('unit_id','tag_owners',this.value,'{{$tag_owner_data->id}}');\"",'','');
    }

</script>****{{csrf_token()}}