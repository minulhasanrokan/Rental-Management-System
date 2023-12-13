<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Edit User Type Information - {{$user_type_data->user_type_name}}</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="user_type_edit_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user_type_name">User Type Name <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="user_type_name" name="user_type_name" placeholder="Enter User Type Name" value="{{$user_type_data->user_type_name}}" onkeyup="check_duplicate_value('user_type_name','user_types',this.value,'{{$user_type_data->id}}');" required>
                                    <div class="input-error" style="display:none; color: red;" id="user_type_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user_type_code">User Type Code <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="user_type_code" name="user_type_code" placeholder="Enter User Type Code" value="{{$user_type_data->user_type_code}}" onkeyup="check_duplicate_value('user_type_code','user_types',this.value,'{{$user_type_data->id}}');" required>
                                    <div class="input-error" style="display:none; color: red;" id="user_type_code_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user_type_title">User Type Title <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="user_type_title" name="user_type_title" placeholder="Enter User Type Title" value="{{$user_type_data->user_type_title}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="user_type_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="user_type_deatils">User Type Details</label>
                                    <textarea class="form-control" id="user_type_deatils" name="user_type_deatils">{{$user_type_data->user_type_deatils}}</textarea>
                                    <div class="input-error" style="display:none; color: red;" id="user_type_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @foreach($user_right_data as $data)
                            <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$encrypt_id}}','{{$user_type_data->user_type_name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
                        @endforeach
                        <button type="button" style="float:right" onclick="save_user_type_info_data();" class="btn btn-primary">Update User Type Information</button>
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

        $('#user_type_deatils').summernote({
            height: 200,
            focus: true
        })
    });

    function save_user_type_info_data(){

        if( form_validation('user_type_name*user_type_code*user_type_title','User Type Name*User Type Code*User Type Title')==false ){

            return false;
        }

        var user_type_name = $("#user_type_name").val();
        var user_type_code = $("#user_type_code").val();
        var user_type_title = $("#user_type_title").val();
        var user_type_deatils = $("#user_type_deatils").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        form_data.append("user_type_name", user_type_name);
        form_data.append("user_type_code", user_type_code);
        form_data.append("user_type_title", user_type_title);
        form_data.append("user_type_deatils", user_type_deatils);
        form_data.append("update_id", '{{$user_type_data->id}}');
        form_data.append("_token", token);

        freeze_window(0);

        http.open("POST","{{route('reference_data.user.type.edit',$user_type_data->id)}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_user_type_info_data_response;
    }

    function save_user_type_info_data_response(){

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