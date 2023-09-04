<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Set User Group Right</h3>
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
                            @php
                                if(isset($all_right_data['right_group_arr']) && !empty($all_right_data['right_group_arr'])){

                                    foreach($all_right_data['right_group_arr'] as $right_group){
                            @endphp
                                        <div class="col-md-12">
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">{{$right_group['g_name']}}</legend>
                                                @php
                                                    if(isset($all_right_data['right_cat_arr'][$right_group['g_id']]) && !empty($all_right_data['right_cat_arr'][$right_group['g_id']])){

                                                        foreach($all_right_data['right_cat_arr'][$right_group['g_id']] as $right_cat){
                                                @endphp     <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <h4>{{$right_cat['c_name']}}</h5>
                                                                        @php
                                                                            if(isset($all_right_data['right_arr'][$right_group['g_id']][$right_cat['c_id']]) && !empty($all_right_data['right_arr'][$right_group['g_id']][$right_cat['c_id']])){

                                                                                foreach($all_right_data['right_arr'][$right_group['g_id']][$right_cat['c_id']] as $right){
                                                                        @endphp
                                                                                    <input type="checkbox" class="datetime" id="startTime" name="startTime" placeholder="Start Time" />
                                                                                    <label class="control-label input-label" for="startTime">{{$right['r_name']}} <i class="fa {{$right['r_icon']}}"></i></label>
                                                                                    <br>
                                                                        @php
                                                                                }
                                                                            }
                                                                        @endphp
                                                                    </div>
                                                                </div>
                                                            </div>
                                                @php
                                                        }
                                                    }
                                                @endphp
                                            </fieldset>
                                        </div>
                            @php
                                    }
                                }
                            @endphp
                  
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @foreach($user_right_data as $data)
                            <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$group_data->id}}','{{$group_data->group_name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
                        @endforeach
                        <button type="button" style="float:right" onclick="save_user_group_info_data();" class="btn btn-primary">Set User Group Right</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
        <!--/.col (left) -->
    </div>
    <!-- /.row -->
</div>
<style type="text/css">
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        width:inherit; /* Or auto */
        padding:0 10px; /* To give a bit of padding on the left and right */
        border-bottom:none;
    }
</style>

<script>
    $(function () {

        $('#group_deatils').summernote({
            height: 64,
            focus: true
        })
    });

    function readUrl(input,view_id){
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e){
                $('#'+view_id).attr('src', e.target.result).width(80).height(80);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

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

                location.replace('<?php echo url('/login');?>');
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
                    $(".input-error").delay(3000).fadeOut(800);
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