<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Edit User Group Information - {{$group_data->group_name}}</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="user_group_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="group_name">User Group Name</label>
                                    <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter User Group Name" onkeyup="check_duplicate_value('group_name','user_groups',this.value,'{{$group_data->id}}');" value="{{$group_data->group_name}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="group_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="group_code">User Group Code</label>
                                    <input type="text" class="form-control" id="group_code" name="group_code" placeholder="Enter User Group Code" onkeyup="check_duplicate_value('group_code','user_groups',this.value,'{{$group_data->id}}');" value="{{$group_data->group_code}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="group_code_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="group_title">User Group Title</label>
                                    <input type="text" class="form-control" id="group_title" name="group_title" placeholder="Enter User Group Title" value="{{$group_data->group_title}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="group_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="group_deatils">User Group Details</label>
                                    <textarea class="form-control" id="group_deatils" name="group_deatils">{{$group_data->group_deatils}}</textarea>
                                    <div class="input-error" style="display:none; color: red;" id="group_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="group_logo">User Group Photo</label>
                                    <input onchange="readUrl(this,'group_logo_photo');" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" id="group_logo" name="group_logo" placeholder="Enter User Group Photo" required>
                                    <input type="hidden" name="hidden_group_logo" id="hidden_group_logo" value="{{$group_data->group_logo}}">
                                    <div class="input-error" style="display:none; color: red;" id="group_logo_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <img class="rounded avatar-lg" width="80" height="80" id="group_logo_photo" src="{{asset('uploads/user_group')}}/{{!empty($group_data->group_logo)?$group_data->group_logo:'user_group.png'}}"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="group_icon">User Group Icon</label>
                                    <input onchange="readUrl(this,'group_icon_photo');" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" id="group_icon" name="group_icon" placeholder="Enter User Group Icon" required>
                                    <input type="hidden" name="hidden_group_icon" id="hidden_group_icon" value="{{$group_data->group_icon}}">
                                    <div class="input-error" style="display:none; color: red;" id="group_iconn_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <img class="rounded avatar-lg" width="80" height="80" id="group_icon_photo" src="{{asset('uploads/user_group')}}/{{!empty($group_data->group_icon)?$group_data->group_icon:'user_group_icon.png'}}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @foreach($user_right_data as $data)
                            <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$group_data->id}}','{{$group_data->group_name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
                        @endforeach
                        <button type="button" style="float:right" onclick="save_user_group_info_data();" class="btn btn-primary">Update User Group</button>
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

        $('#group_deatils').summernote({
            height: 64,
            focus: true
        })
    });
    
    function save_user_group_info_data(){

        if( form_validation('group_name*group_code*group_title','User Group Name*User Group Code*User Group Title')==false ){

            return false;
        }

        var hidden_group_logo  = $("#hidden_group_logo").val();
        var hidden_group_icon  = $("#hidden_group_icon").val();

        if(hidden_group_logo==''){

            if( form_validation('group_logo','User Group Photo')==false ){

                return false;
            }
        }

        if(hidden_group_icon==''){

            if( form_validation('group_icon','User Group Icon')==false ){

                return false;
            }
        }

        var group_name = $("#group_name").val();
        var group_code = $("#group_code").val();
        var group_title = $("#group_title").val();
        var group_deatils = $("#group_deatils").val();

        //check_duplicate_value('group_name','user_groups',group_name,0);
        //check_duplicate_value('group_code','user_groups',group_code,0);

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        var group_logo = $('#group_logo')[0].files;
        var group_icon = $('#group_icon')[0].files;

        form_data.append('group_logo',group_logo[0]);
        form_data.append('group_icon',group_icon[0]);

        form_data.append("group_name", group_name);
        form_data.append("group_code", group_code);
        form_data.append("group_title", group_title);
        form_data.append("group_deatils", group_deatils);
        form_data.append("hidden_group_logo", hidden_group_logo);
        form_data.append("hidden_group_icon", hidden_group_icon);
        form_data.append("update_id", '{{$group_data->id}}');
        form_data.append("_token", token);

        http.open("POST","{{route('user_management.user_group.edit',$group_data->id)}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_user_group_info_data_response;
    }

    function save_user_group_info_data_response(){

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

                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                    $('input[name="_token"]').attr('value', data.csrf_token);
                }
            }

            document.getElementById("user_group_form").reset();
        }
    }

</script>****{{csrf_token()}}