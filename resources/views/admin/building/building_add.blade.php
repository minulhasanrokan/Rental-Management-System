<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Add Building Information</h3>
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
                                    <label for="building_name">Building Name <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="building_name" name="building_name" placeholder="Enter Building Name" onkeyup="check_duplicate_value('building_name','buildings',this.value,0);" required>
                                    <div class="input-error" style="display:none; color: red;" id="building_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="building_code">Building Code <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="building_code" name="building_code" placeholder="Enter Building Code" onkeyup="check_duplicate_value('building_code','buildings',this.value,0);" required>
                                    <div class="input-error" style="display:none; color: red;" id="building_code_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="building_title">Building Title <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="building_title" name="building_title" placeholder="Enter Building Title" onkeyup="check_duplicate_value('building_title','buildings',this.value,0);" required>
                                    <div class="input-error" style="display:none; color: red;" id="building_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="building_address">Building Address <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="building_address" name="building_address" placeholder="Enter Building Address" required>
                                    <div class="input-error" style="display:none; color: red;" id="building_address_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="building_deatils">Building Details</label>
                                    <textarea class="form-control" id="building_deatils" name="building_deatils"></textarea>
                                    <div class="input-error" style="display:none; color: red;" id="building_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="building_logo">Building Logo <span style="color:red;">*</span></label>
                                    <input onchange="readUrl(this,'building_logo_photo');" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" id="building_logo" name="building_logo" placeholder="Enter Building Logo" required>
                                    <input type="hidden" name="hidden_building_logo" id="hidden_building_logo" value="">
                                    <div class="input-error" style="display:none; color: red;" id="building_logo_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <img class="rounded avatar-lg" width="80" height="80" id="building_logo_photo" src="{{asset('uploads/building/building_logo.png')}}"/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="building_photo">Building Image <span style="color:red;">*</span></label>
                                    <input onchange="readUrl(this,'building_photo_photo');" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" id="building_photo" name="building_photo" placeholder="Enter Building Image" required>
                                    <input type="hidden" name="hidden_building_photo" id="hidden_building_photo" value="">
                                    <div class="input-error" style="display:none; color: red;" id="building_logo_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <img class="rounded avatar-lg" width="80" height="80" id="building_photo_photo" src="{{asset('uploads/building/building_logo.png')}}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="button" style="float:right" onclick="save_building_info_data();" class="btn btn-primary">Add Building Information</button>
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

        if( form_validation('building_name*building_code*building_title*building_address*building_logo*building_photo','Building Name*Building Code*Building Title*Building Address*Building Logo* Building Image')==false ){

            return false;
        }

        var building_name = $("#building_name").val();
        var building_code = $("#building_code").val();
        var building_title = $("#building_title").val();
        var building_address = $("#building_address").val();
        var building_deatils = $("#building_deatils").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        var building_logo = $('#building_logo')[0].files;
        form_data.append('building_logo',building_logo[0]);

        var building_photo = $('#building_photo')[0].files;
        form_data.append('building_photo',building_photo[0]);

        form_data.append("building_name", building_name);
        form_data.append("building_code", building_code);
        form_data.append("building_title", building_title);
        form_data.append("building_address", building_address);
        form_data.append("building_deatils", building_deatils);
        form_data.append("_token", token);

        http.open("POST","{{route('floor_management.building.add')}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_building_info_data_response;
    }

    function save_building_info_data_response(){

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

                        document.getElementById("building_form").reset();

                        $('#building_deatils').summernote('reset');

                        $("#building_logo_photo").attr("src","{{asset('uploads/building/building_logo.png')}}");
                        $("#building_photo_photo").attr("src","{{asset('uploads/building/building_logo.png')}}");
                    }

                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                    $('input[name="_token"]').attr('value', data.csrf_token);
                }
            }
        }
    }

</script>****{{csrf_token()}}