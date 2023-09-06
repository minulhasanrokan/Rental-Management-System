<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Add User Information</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="user_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">User Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter User Name" onkeyup="check_duplicate_value('name','users',this.value,0);" required>
                                    <div class="input-error" style="display:none; color: red;" id="name_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">User E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter User E-mail" onkeyup="check_duplicate_value('email','users',this.value,0);" required>
                                    <div class="input-error" style="display:none; color: red;" id="email_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mobile">User Mobile</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter User Mobile" onkeyup="check_duplicate_value('mobile','users',this.value,0);" required>
                                    <div class="input-error" style="display:none; color: red;" id="mobile_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_of_birth">User Date Of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" placeholder="Enter User Date Of Birth" required>
                                    <div class="input-error" style="display:none; color: red;" id="date_of_birth_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="group">User Group</label>
                                    <input type="text" class="form-control" id="group" name="group" placeholder="Enter User Group" required>
                                    <div class="input-error" style="display:none; color: red;" id="group_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sex">User Gender</label>
                                    <input type="text" class="form-control" id="sex" name="sex" placeholder="Enter User Gender" required>
                                    <div class="input-error" style="display:none; color: red;" id="sex_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="blood_group">User Blood Group</label>
                                    <input type="text" class="form-control" id="blood_group" name="blood_group" placeholder="Enter User Blood Group" required>
                                    <div class="input-error" style="display:none; color: red;" id="blood_group_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user_type">User Type</label>
                                    <input type="text" class="form-control" id="user_type" name="user_type" placeholder="Enter User Type" required>
                                    <div class="input-error" style="display:none; color: red;" id="user_type_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="designation">User Designnation</label>
                                    <input type="text" class="form-control" id="designation" name="designation" placeholder="Enter User Designnation" required>
                                    <div class="input-error" style="display:none; color: red;" id="designation_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="department">User Depertment</label>
                                    <input type="text" class="form-control" id="department" name="department" placeholder="Enter User Depertment" required>
                                    <div class="input-error" style="display:none; color: red;" id="department_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="assign_department">User Assign Depertment</label>
                                    <input type="text" class="form-control" id="assign_department" name="assign_department" placeholder="Enter User Assign Depertment" required>
                                    <div class="input-error" style="display:none; color: red;" id="assign_department_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">User Address</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter User Address" required>
                                    <div class="input-error" style="display:none; color: red;" id="address_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="details">User Details</label>
                                    <textarea class="form-control" id="details" name="details"></textarea>
                                    <div class="input-error" style="display:none; color: red;" id="details_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="user_photo">User Photo</label>
                                    <input onchange="readUrl(this,'user_photo_photo');" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" id="user_photo" name="user_photo" placeholder="Enter User Photo" required>
                                    <input type="hidden" name="hidden_user_photo" id="hidden_user_photo" value="">
                                    <div class="input-error" style="display:none; color: red;" id="user_photo_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <img class="rounded avatar-lg" width="80" height="80" id="user_photo_photo" src="{{asset('uploads/user/user.png')}}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="button" style="float:right" onclick="save_user_info_data();" class="btn btn-primary">Add User Group</button>
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

        $('#details').summernote({
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

    function save_user_info_data(){

        if( form_validation('group_name*group_code*group_title*user_photo*group_icon','User Group Name*User Group Code*User Group Title*User Group Photo*User Group Icon')==false ){

            return false;
        }

        var group_name = $("#group_name").val();
        var group_code = $("#group_code").val();
        var group_title = $("#group_title").val();
        var group_deatils = $("#group_deatils").val();

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        var user_photo = $('#user_photo')[0].files;
        var group_icon = $('#group_icon')[0].files;

        form_data.append('user_photo',user_photo[0]);
        form_data.append('group_icon',group_icon[0]);

        form_data.append("group_name", group_name);
        form_data.append("group_code", group_code);
        form_data.append("group_title", group_title);
        form_data.append("group_deatils", group_deatils);
        form_data.append("_token", token);

        http.open("POST","{{route('user_management.user.add')}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_user_info_data_response;
    }

    function save_user_info_data_response(){

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

            document.getElementById("user_form").reset();
        }
    }

</script>****{{csrf_token()}}