<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Edit Building Information - {{$building_data->building_name}}</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="building_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="building_name">Building Name</label>
                                    <input type="text" class="form-control" id="building_name" name="building_name" placeholder="Enter Building Name" onkeyup="check_duplicate_value('building_name','buildings',this.value,'{{$building_data->id}}');" value="{{$building_data->building_name}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="building_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="building_code">Building Code</label>
                                    <input type="text" class="form-control" id="building_code" name="building_code" placeholder="Enter Building Code" onkeyup="check_duplicate_value('building_code','buildings',this.value,'{{$building_data->id}}');" value="{{$building_data->building_code}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="building_code_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="building_title">Building Title</label>
                                    <input type="text" class="form-control" id="building_title" name="building_title" placeholder="Enter Building Title" onkeyup="check_duplicate_value('building_title','buildings',this.value,'{{$building_data->id}}');" value="{{$building_data->building_title}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="building_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="building_address">Building Address <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="building_address" name="building_address" placeholder="Enter Building Address" value="{{$building_data->building_address}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="building_address_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="building_deatils">Building Details</label>
                                    <textarea class="form-control" id="building_deatils" name="building_deatils">{{$building_data->building_deatils}}</textarea>
                                    <div class="input-error" style="display:none; color: red;" id="building_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="building_logo">Building Photo</label>
                                    <input onchange="readUrl(this,'building_logo_photo');" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" id="building_logo" name="building_logo" placeholder="Enter Building Photo" required>
                                    <input type="hidden" name="hidden_building_logo" id="hidden_building_logo" value="{{$building_data->building_logo}}">
                                    <div class="input-error" style="display:none; color: red;" id="building_logo_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <img class="rounded avatar-lg" width="80" height="80" id="building_logo_photo" src="{{asset('uploads/building')}}/{{!empty($building_data->building_logo)?$building_data->building_logo:'building_logo.png'}}"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="building_photo">Building Image</label>
                                    <input onchange="readUrl(this,'building_photo_photo');" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" id="building_photo" name="building_photo" placeholder="Enter Building Image" required>
                                    <input type="hidden" name="hidden_building_photo" id="hidden_building_photo" value="{{$building_data->building_photo}}">
                                    <div class="input-error" style="display:none; color: red;" id="building_photo_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <img class="rounded avatar-lg" width="80" height="80" id="building_photo_photo" src="{{asset('uploads/building')}}/{{!empty($building_data->building_photo)?$building_data->building_photo:'building_logo.png'}}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @foreach($user_right_data as $data)
                            <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$encrypt_id}}','{{$building_data->building_name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
                        @endforeach
                        <button type="button" style="float:right" onclick="save_building_info_data();" class="btn btn-primary">Update Building</button>
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

        $('#building_deatils').summernote({
            height: 60,
            focus: true
        })
    });
    
    function save_building_info_data(){

        if( form_validation('building_name*building_code*building_title*building_address','Building Name*Building Code*Building Title*Building Address')==false ){

            return false;
        }

        var hidden_building_logo  = $("#hidden_building_logo").val();
        var hidden_building_photo  = $("#hidden_building_photo").val();

        if(hidden_building_logo==''){

            if( form_validation('building_logo','Building Logo')==false ){

                return false;
            }
        }

        if(hidden_building_photo==''){

            if( form_validation('building_photo','Building Image')==false ){

                return false;
            }
        }

        var building_name = $("#building_name").val();
        var building_code = $("#building_code").val();
        var building_title = $("#building_title").val();
        var building_address = $("#building_address").val();
        var building_deatils = $("#building_deatils").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        var building_logo = $('#building_logo')[0].files;
        var building_photo = $('#building_photo')[0].files;

        form_data.append('building_logo',building_logo[0]);
        form_data.append('building_photo',building_photo[0]);

        form_data.append("building_name", building_name);
        form_data.append("building_code", building_code);
        form_data.append("building_title", building_title);
        form_data.append("building_address", building_address);
        form_data.append("building_deatils", building_deatils);
        form_data.append("hidden_building_logo", hidden_building_logo);
        form_data.append("hidden_building_photo", hidden_building_photo);
        form_data.append("update_id", '{{$building_data->id}}');
        form_data.append("_token", token);

        freeze_window(0);

        http.open("POST","{{route('floor_management.building.edit',$building_data->id)}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_building_info_data_response;
    }

    function save_building_info_data_response(){

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

</script>****{{csrf_token()}}