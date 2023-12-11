@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                    <div class="card-header">
                        <h3 class="card-title">Add Notice Information</h3>
                    </div>
                    <div class="card-header" style="background-color: white;">
                        {!!$menu_data!!}
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="notice_form" method="post" autocomplete="off">
                        @csrf
                        <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notice_title">Notice Title <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="notice_title" name="notice_title" placeholder="Enter Notice Title" required>
                                    <div class="input-error" style="display:none; color: red;" id="notice_title_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="notice_group">Notice For</label>
                                        <div id="notice_group_container"></div>
                                        <div class="input-error" style="display:none; color: red;" id="notice_group_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="user_id">User </label>
                                        <div id="user_id_container">
                                            <select class="form-control select" style="width: 100%;" name="user_id" id="user_id">
                                                <option value="">All User</option>
                                            </select>
                                        </div>
                                        <div class="input-error" style="display:none; color: red;" id="user_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="notice_date">Notice Date <span style="color:red;">*</span></label>
                                        <input type="date" class="form-control" id="notice_date" name="notice_date" placeholder="Enter Tenant Name" required>
                                        <div class="input-error" style="display:none; color: red;" id="notice_date_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="notice_status">Notice Status <span style="color:red;">*</span></label>
                                        <div id="notice_status_container">
                                            <select class="form-control select" style="width: 100%;" name="notice_status" id="notice_status">
                                                <option value="1">Published</option>
                                                <option value="0">Un Published</option>
                                            </select>
                                        </div>
                                        <div class="input-error" style="display:none; color: red;" id="notice_status_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="email_status">E-mail Status <span style="color:red;">*</span></label>
                                        <div id="notice_status_container">
                                            <select class="form-control select" style="width: 100%;" name="email_status" id="email_status">
                                                <option value="0">No</option>
                                                <option value="1">Yes</option>
                                            </select>
                                        </div>
                                        <div class="input-error" style="display:none; color: red;" id="email_status_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notice_details">Notice Details <span style="color:red;">*</span></label>
                                        <textarea class="form-control" id="notice_details" name="notice_details"></textarea>
                                        <div class="input-error" style="display:none; color: red;" id="notice_details_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notice_file">Notice File</label>
                                        <input type="file" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,text/plain, application/pdf, image/*" class="form-control" id="notice_file" name="notice_file">
                                        <input type="hidden" name="hidden_notice_file" id="hidden_notice_file" value="">
                                        <div class="input-error" style="display:none; color: red;" id="notice_file_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="button" style="float:right" onclick="save_complain_info_data();" class="btn btn-primary">Add Notice</button>
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

            $('#notice_details').summernote({
                height: 150,
                focus: true
            })
        });

        function save_complain_info_data(){

            if( form_validation('notice_title*notice_date*notice_status*notice_details','Notice Title*Notice Date*Notice Status*Notice Details')==false ){

                return false;
            }

            var notice_title = $("#notice_title").val();
            var notice_group = $("#notice_group").val();
            var user_id = $("#user_id").val();
            var notice_date = $("#notice_date").val();
            var notice_status = $("#notice_status").val();
            var email_status = $("#email_status").val();
            var notice_details = $("#notice_details").val();

            var token = $('meta[name="csrf-token"]').attr('content');

            var form_data = new FormData();

            var notice_file = $('#notice_file')[0].files;

            form_data.append('notice_file',notice_file[0]);
            
            form_data.append("notice_title", notice_title);
            form_data.append("notice_group", notice_group);
            form_data.append("user_id", user_id);
            form_data.append("notice_date", notice_date);
            form_data.append("notice_status", notice_status);
            form_data.append("email_status", email_status);
            form_data.append("notice_details", notice_details);

            form_data.append("_token", token);

            freeze_window(0);

            http.open("POST","{{route('notice_manage.manage.add')}}",true);
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

                        if(data.alert_type=='success'){

                            document.getElementById("notice_form").reset();

                            $('#notice_details').summernote('reset');

                            $("#notice_status").val('1');
                            load_drop_down_by_id('users','id,name','user_id','user_id_container','All User',0,1,'',0,0,'group','','','');
                        }

                        $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                        $('input[name="_token"]').attr('value', data.csrf_token);
                    }
                }
            }
        }

        load_drop_down('user_groups','id,group_name','notice_group','notice_group_container','All Group',0,1,'',0,'onchange="load_drop_down_by_id(\'users\',\'id,name\',\'user_id\',\'user_id_container\',\'All User\',0,1,\'\',0,this.value,\'group\',\'\',\'\',\'\')"');

    </script>
@endsection