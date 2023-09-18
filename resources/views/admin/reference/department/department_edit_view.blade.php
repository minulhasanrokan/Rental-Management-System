<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Edit Department Information - {{$department_data->department_name}}</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="department_edit_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="department_name">Department Name <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="department_name" name="department_name" placeholder="Enter Department Name" value="{{$department_data->department_name}}" onkeyup="check_duplicate_value('department_name','departments',this.value,'{{$department_data->id}}');" required>
                                    <div class="input-error" style="display:none; color: red;" id="department_name_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="department_code">Department Code <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="department_code" name="department_code" placeholder="Enter Department Code" value="{{$department_data->department_code}}" onkeyup="check_duplicate_value('department_code','departments',this.value,'{{$department_data->id}}');" required>
                                    <div class="input-error" style="display:none; color: red;" id="department_code_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="department_title">Department Title <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="department_title" name="department_title" placeholder="Enter Department Title" value="{{$department_data->department_title}}" required>
                                    <div class="input-error" style="display:none; color: red;" id="department_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="department_deatils">Department Details</label>
                                    <textarea class="form-control" id="department_deatils" name="department_deatils">{{$department_data->department_deatils}}</textarea>
                                    <div class="input-error" style="display:none; color: red;" id="department_deatils_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @foreach($user_right_data as $data)
                            <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$department_data->id}}','{{$department_data->department_name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
                        @endforeach
                        <button type="button" style="float:right" onclick="save_department_info_data();" class="btn btn-primary">Update Department Information</button>
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

        $('#department_deatils').summernote({
            height: 200,
            focus: true
        })
    });

    function save_department_info_data(){

        if( form_validation('department_name*department_code*department_title','Department Name*Department Code*Department Title')==false ){

            return false;
        }

        var department_name = $("#department_name").val();
        var department_code = $("#department_code").val();
        var department_title = $("#department_title").val();
        var department_deatils = $("#department_deatils").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        form_data.append("department_name", department_name);
        form_data.append("department_code", department_code);
        form_data.append("department_title", department_title);
        form_data.append("department_deatils", department_deatils);
        form_data.append("update_id", '{{$department_data->id}}');
        form_data.append("_token", token);

        http.open("POST","{{route('reference_data.department.edit',$department_data->id)}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_department_info_data_response;
    }

    function save_department_info_data_response(){

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