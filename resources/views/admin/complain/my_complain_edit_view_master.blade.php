@extends('admin.admin_master')

@section('content')
    @php

        $user_id = Session::get(config('app.app_session_name').'.id');

        $building_data = DB::table('buildings as a')
            ->join('rents as b', 'a.id', '=', 'b.building_id')
            ->select('a.id','a.building_name')
            ->where('a.delete_status',0)
            ->where('b.delete_status',0)
            ->where('b.close_status',0)
            ->where('a.status',1)
            ->where('b.status',1)
            ->where('b.tenant_id',$user_id)
            ->orWhere('a.id',$complain_data->building_id)
            ->groupBy('a.id','a.building_name')
            ->get();

    @endphp
    <div class="container-fluid" style="padding-top: 5px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                    <div class="card-header">
                        <h3 class="card-title">Edit Complain Information</h3>
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="building_id">Building <span style="color:red;">*</span></label>
                                        <div id="building_id_container">
                                            <select class="form-control select" onchange="load_drop_down_level_by_id(this.value,'')" style="width: 100%;" name="building_id" id="building_id">
                                                <option value="">Select Building</option>
                                                @foreach($building_data as $data)
                                                    <option value="{{$data->id}}">{{$data->building_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
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
                                            <select class="form-control select" style="width: 100%; float: left;" name="unit_id" id="unit_id">
                                                <option value="">Select Unit</option>
                                            </select>
                                        </div>
                                        <div class="input-error" style="display:none; color: red;" id="unit_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="complain_title">Complain Title <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="complain_title" name="complain_title" placeholder="Enter Complain Title" value="{{$complain_data->complain_title}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="complain_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="complain_details">Complain Details <span style="color:red;">*</span></label>
                                        <textarea class="form-control" id="complain_details" name="complain_details">{{$complain_data->complain_details}}</textarea>
                                        <div class="input-error" style="display:none; color: red;" id="complain_details_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            @foreach($user_right_data as $data)
                                <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$complain_data->id}}','{{$complain_data->complain_title}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
                            @endforeach
                            <button type="button" style="float:right" onclick="save_complain_info_data();" class="btn btn-primary">Update Complain Information</button>
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

            if( form_validation('building_id*level_id*unit_id*complain_title*complain_details','Building Name*Level Name*Unit Name*Complain Title*Complain Details')==false ){

                return false;
            }

            var building_id = $("#building_id").val();
            var level_id = $("#level_id").val();
            var unit_id = $("#unit_id").val();
            var complain_title = $("#complain_title").val();
            var complain_details = $("#complain_details").val();

            var token = $('meta[name="csrf-token"]').attr('content');

            var form_data = new FormData();
            
            form_data.append("building_id", building_id);
            form_data.append("level_id", level_id);
            form_data.append("unit_id", unit_id);
            form_data.append("complain_title", complain_title);
            form_data.append("complain_details", complain_details);
            form_data.append("update_id", {{$complain_data->id}});

            form_data.append("_token", token);

            freeze_window(0);

            http.open("POST","{{route('complain_management.my_complain.edit',$complain_data->id)}}",true);
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

                        $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                        $('input[name="_token"]').attr('value', data.csrf_token);
                    }
                }
            }
        }

        function load_drop_down_level_by_id(data_value,selected_value) {

            var value = 0;
            var id_value = 0;

            if(data_value!=''){

                value = data_value;
            }

            if(selected_value!=''){

                id_value = selected_value;
            }

            var http = createObject();

            var data="&value="+value+"&id_value="+id_value;

            if( http ) {
                
                http.onreadystatechange = function() {

                    if( http.readyState == 4 ) {

                        if( http.status == 200 ){

                            var reponse=trim(http.responseText).split("\*\*\*\*");

                            if(reponse[0]=='Session Expire' || reponse[0]=='Right Not Found'){

                                location.replace('<?php echo url('/dashboard/logout');?>');
                            }
                            else{

                                data ='<select onchange="load_drop_down_unit_by_id(this.value,\'\')" class="form-control select" style="width: 100%;" name="level_id" id="level_id"><option value="">Select Level</option>';

                                var json_data = JSON.parse(reponse[0]);

                                var data_length = Object.keys(json_data).length;

                                for(var i=0; i<data_length; i++){

                                    data +='<option value="'+json_data[i]['id']+'">'+json_data[i]['level_name']+'</option>';
                                }

                                data +='</select>';

                                document.getElementById('level_id_container').innerHTML = data;

                                $("#level_id").val(selected_value);
                            }
                        }
                    }
                }

                http.open("GET","{{route('admin.get.level.data.by.building.id')}}"+"/"+value+"/"+id_value,true);
                http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                http.send(data);
            }
        }

        function load_drop_down_unit_by_id(data_value,selected_value) {

            var value = 0;
            var id_value = 0;

            if(data_value!=''){

                value = data_value;
            }

            if(selected_value!=''){

                id_value = selected_value;
            }

            var http = createObject();

            var data="&value="+value+"&id_value="+id_value;

            if( http ) {
                
                http.onreadystatechange = function() {

                    if( http.readyState == 4 ) {

                        if( http.status == 200 ){

                            var reponse=trim(http.responseText).split("\*\*\*\*");

                            if(reponse[0]=='Session Expire' || reponse[0]=='Right Not Found'){

                                location.replace('<?php echo url('/dashboard/logout');?>');
                            }
                            else{

                                data ='<select class="form-control select" style="width: 100%;" name="unit_id" id="unit_id"><option value="">Select Level</option>';

                                var json_data = JSON.parse(reponse[0]);

                                var data_length = Object.keys(json_data).length;

                                for(var i=0; i<data_length; i++){

                                    data +='<option value="'+json_data[i]['id']+'">'+json_data[i]['unit_name']+'</option>';
                                }

                                data +='</select>';

                                document.getElementById('unit_id_container').innerHTML = data;

                                $("#unit_id").val(selected_value);
                            }
                        }
                    }
                }

                http.open("GET","{{route('admin.get.unit.data.by.level.id')}}"+"/"+value+"/"+id_value,true);
                http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                http.send(data);
            }
        }

        $("#building_id").val('{{$complain_data->building_id}}');

        load_drop_down_level_by_id('{{$complain_data->building_id}}','{{$complain_data->level_id}}');
        load_drop_down_unit_by_id('{{$complain_data->level_id}}','{{$complain_data->unit_id}}');

    </script>
@endsection