<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Edit Gender Information - {{$gender_data->gender_name}}</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="gender_edit_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender_name">Gender Name <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="gender_name" name="gender_name" placeholder="Enter Gender Name" value="{{$gender_data->gender_name}}" onkeyup="check_duplicate_value('gender_name','genders',this.value,'{{$gender_data->id}}');" required>
                                    <div class="input-error" style="display:none; color: red;" id="gender_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender_code">Gender Code <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="gender_code" name="gender_code" placeholder="Enter Gender Code" value="{{$gender_data->gender_code}}" onkeyup="check_duplicate_value('gender_code','genders',this.value,'{{$gender_data->id}}');" required>
                                    <div class="input-error" style="display:none; color: red;" id="gender_code_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender_title">Gender Title <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="gender_title" name="gender_title" placeholder="Enter Gender Title" value="{{$gender_data->gender_title}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="gender_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="gender_deatils">Gender Details</label>
                                    <textarea class="form-control" id="gender_deatils" name="gender_deatils">{{$gender_data->gender_deatils}}</textarea>
                                    <div class="input-error" style="display:none; color: red;" id="gender_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @foreach($user_right_data as $data)
                            <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$gender_data->id}}','{{$gender_data->gender_name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
                        @endforeach
                        <button type="button" style="float:right" onclick="save_gender_info_data();" class="btn btn-primary">Update Gender Information</button>
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

        $('#gender_deatils').summernote({
            height: 200,
            focus: true
        })
    });

    function save_gender_info_data(){

        if( form_validation('gender_name*gender_code*gender_title','Gender Name*Gender Code*Gender Title')==false ){

            return false;
        }

        var gender_name = $("#gender_name").val();
        var gender_code = $("#gender_code").val();
        var gender_title = $("#gender_title").val();
        var gender_deatils = $("#gender_deatils").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        form_data.append("gender_name", gender_name);
        form_data.append("gender_code", gender_code);
        form_data.append("gender_title", gender_title);
        form_data.append("gender_deatils", gender_deatils);
        form_data.append("update_id", '{{$gender_data->id}}');
        form_data.append("_token", token);

        freeze_window(0);

        http.open("POST","{{route('reference_data.gender.edit',$gender_data->id)}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_gender_info_data_response;
    }

    function save_gender_info_data_response(){

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