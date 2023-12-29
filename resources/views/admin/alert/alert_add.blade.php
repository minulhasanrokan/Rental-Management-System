<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Add Message Information</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="alert_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="alert_title">Message  Title <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="alert_title" name="alert_title" placeholder="Enter Message  Title" required>
                                <div class="input-error" style="display:none; color: red;" id="alert_title_error" style="display: inline-block; width:100%; color: red;"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="alert_group">Message For</label>
                                    <div id="alert_group_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="alert_group_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email_status">E-mail Status <span style="color:red;">*</span></label>
                                    <div id="alert_status_container">
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
                                    <label for="alert_details">Message  Details <span style="color:red;">*</span></label>
                                    <textarea class="form-control" id="alert_details" name="alert_details"></textarea>
                                    <div class="input-error" style="display:none; color: red;" id="alert_details_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="button" style="float:right" onclick="save_complain_info_data();" class="btn btn-primary">Send Message</button>
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

    /*$(function () {

        $('#alert_details').summernote({
            height: 150,
            focus: true
        })
    });*/

    function save_complain_info_data(){

        if( form_validation('alert_title*alert_details','Message Title*Message Details')==false ){

            return false;
        }

        var alert_title = $("#alert_title").val();
        var alert_group = $("#alert_group").val();
        var user_id = $("#user_id").val();
        var email_status = $("#email_status").val();
        var alert_details = $("#alert_details").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();
        
        form_data.append("alert_title", alert_title);
        form_data.append("alert_group", alert_group);
        form_data.append("user_id", user_id);
        form_data.append("email_status", email_status);
        form_data.append("alert_details", alert_details);

        form_data.append("_token", token);

        freeze_window(0);

        http.open("POST","{{route('sms_email_alert.manage.add')}}",true);
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

                    if(data.alert_type=='success'){

                        document.getElementById("alert_form").reset();

                        // $('#alert_details').summernote('reset');

                        $("#alert_status").val('1');
                        load_drop_down_by_id('users','id,name','user_id','user_id_container','All User',0,1,'',0,0,'group','','','');
                    }
                }

                // hide all input error.............
                $(".input-error").delay(3000).fadeOut(800);
            }
        }
    }

    load_drop_down('user_groups','id,group_name','alert_group','alert_group_container','All Group',0,1,'',0,'onchange="load_drop_down_by_id(\'users\',\'id,name\',\'user_id\',\'user_id_container\',\'All User\',0,1,\'\',0,this.value,\'group\',\'\',\'\',\'\')"');

</script>****{{csrf_token()}}