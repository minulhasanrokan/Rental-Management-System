@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                    <div class="card-header">
                        <h3 class="card-title">Edit User Information - {{$user_data->name}}</h3>
                    </div>
                    <div class="card-header" style="background-color: white;">
                        {!!$menu_data!!}
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="update_user_form" method="post" autocomplete="off">
                        @csrf
                        <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">User Name <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter User Name" value="{{$user_data->name}}" required>
                                        <div class="input-error" style="display:none; color: red;" id="name_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">User E-mail <span style="color:red;">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter User E-mail" value="{{$user_data->email}}" onkeyup="check_duplicate_value('email','users',this.value,'{{$user_data->id}}');" required>
                                        <div class="input-error" style="display:none; color: red;" id="email_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mobile">User Mobile <span style="color:red;">*</span></label>
                                        <input type="text" value="{{$user_data->mobile}}" class="form-control" id="mobile" name="mobile" placeholder="Enter User Mobile" onkeyup="check_duplicate_value('mobile','users',this.value,'{{$user_data->id}}');" required>
                                        <div class="input-error" style="display:none; color: red;" id="mobile_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_of_birth">User Date Of Birth <span style="color:red;">*</span></label>
                                        <input type="date" value="{{$user_data->date_of_birth}}" class="form-control" id="date_of_birth" name="date_of_birth" placeholder="Enter User Date Of Birth" required>
                                        <div class="input-error" style="display:none; color: red;" id="date_of_birth_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sex">User Gender <span style="color:red;">*</span></label>
                                        <div id="sex_container"></div>
                                        <div class="input-error" style="display:none; color: red;" id="sex_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="blood_group">User Blood Group <span style="color:red;">*</span></label>
                                        <div id="blood_group_container"></div>
                                        <div class="input-error" style="display:none; color: red;" id="blood_group_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="group">User Group <span style="color:red;">*</span></label>
                                        <div id="group_container"></div>
                                        <div class="input-error" style="display:none; color: red;" id="group_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="user_type">User Type <span style="color:red;">*</span></label>
                                        <div id="user_type_container"></div>
                                        <div class="input-error" style="display:none; color: red;" id="user_type_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="department">User Depertment <span style="color:red;">*</span></label>
                                        <div id="department_container"></div>
                                        <div class="input-error" style="display:none; color: red;" id="department_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="assign_department">User Assign Depertment <span style="color:red;">*</span></label>
                                        <div id="assign_department_container"></div>
                                        <div class="input-error" style="display:none; color: red;" id="assign_department_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="designation">User Designation <span style="color:red;">*</span></label>
                                        <div id="designation_container"></div>
                                        <div class="input-error" style="display:none; color: red;" id="designation_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address">User Address <span style="color:red;">*</span></label>
                                        <input value="{{$user_data->address}}" type="text" class="form-control" id="address" name="address" placeholder="Enter User Address" required>
                                        <div class="input-error" style="display:none; color: red;" id="address_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="details">User Details</label>
                                        <textarea class="form-control" id="details" name="details">{{$user_data->details}}</textarea>
                                        <div class="input-error" style="display:none; color: red;" id="details_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="user_photo">User Photo <span style="color:red;">*</span></label>
                                        <input onchange="readUrl(this,'user_photo_photo');" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" id="user_photo" name="user_photo" placeholder="Enter User Photo" required>
                                        <input type="hidden" name="hidden_user_photo" id="hidden_user_photo" value="{{$user_data->user_photo}}">
                                        <div class="input-error" style="display:none; color: red;" id="user_photo_error" style="display: inline-block; width:100%; color: red;"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <img class="rounded avatar-lg" width="80" height="80" id="user_photo_photo" src="{{asset('uploads/user')}}/{{!empty($user_data->user_photo)?$user_data->user_photo:'user.png'}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            @foreach($user_right_data as $data)
                                <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$encrypt_id}}','{{$user_data->name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
                            @endforeach
                            <button type="button" style="float:right" onclick="update_user_info_data();" class="btn btn-primary">Update User</button>
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

        function update_user_info_data(){

            if( form_validation('name*email*mobile*date_of_birth*sex*blood_group*group*user_type*department*assign_department*designation*address','User Name*User E-mail*User Mobile*User Date Of Birth*User Gender*User Blood Group*User Group*User Type*User Department*User Assign Department*User Designation*User Address')==false ){

                return false;
            }

            var mobile = $("#mobile").val();

            var status = check_mobile_number(mobile);

            if(status==false){

                alert("Please Input Valied Mobile Number");

                $("#mobile").focus();

                return false;
            }

            var date_of_birth = $("#date_of_birth").val();

            var status = check_date_of_birth(date_of_birth,18);

            if(status==false){

                alert("Please Input Valied Date Of Birth");

                $("#mobile").focus();

                return false;
            }

            var hidden_user_photo  = $("#hidden_user_photo").val();

            if(hidden_user_photo==''){

                if( form_validation('user_photo','User Photo')==false ){

                    return false;
                }
            }

            var name = $("#name").val();
            var email = $("#email").val();
            var mobile = $("#mobile").val();
            var date_of_birth = $("#date_of_birth").val();
            var sex = $("#sex").val();
            var blood_group = $("#blood_group").val();
            var group = $("#group").val();
            var user_type = $("#user_type").val();
            var designation = $("#designation").val();
            var department = $("#department").val();
            var assign_department = $("#assign_department").val();
            var address = $("#address").val();
            var details = $("#details").val();
            var hidden_user_photo = $("#hidden_user_photo").val();

            if(assign_department==''){

                alert('Assign Department Is Mandatory');
                return false;
            }

            var token = $('meta[name="csrf-token"]').attr('content');

            var form_data = new FormData();

            var user_photo = $('#user_photo')[0].files;

            form_data.append('user_photo',user_photo[0]);

            form_data.append("name", name);
            form_data.append("email", email);
            form_data.append("mobile", mobile);
            form_data.append("date_of_birth", date_of_birth);
            form_data.append("sex", sex);
            form_data.append("blood_group", blood_group);
            form_data.append("group", group);
            form_data.append("user_type", user_type);
            form_data.append("designation", designation);
            form_data.append("department", department);
            form_data.append("assign_department", assign_department);
            form_data.append("address", address);
            form_data.append("details", details);
            form_data.append("hidden_user_photo", hidden_user_photo);
            form_data.append("update_id", '{{$user_data->id}}');
            form_data.append("_token", token);

            freeze_window(0);

            http.open("POST","{{route('user_management.user.edit',$user_data->id)}}",true); 
            http.setRequestHeader("X-CSRF-TOKEN",token);
            http.send(form_data);
            http.onreadystatechange = update_user_info_data_response;
        }

        function update_user_info_data_response(){

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
                    }

                    // hide all input error.............
                    $(".input-error").delay(3000).fadeOut(800);
                }
            }
        }

        load_drop_down('genders','id,gender_name','sex','sex_container','Select Gender',0,0,'{{$user_data->sex}}',0,'');
        load_drop_down('blood_groups','id,blood_group_name','blood_group','blood_group_container','Select Blood Group',0,0,'{{$user_data->blood_group}}',0,'');
        load_drop_down('user_groups','id,group_name','group','group_container','Select Group',0,0,'{{$user_data->group}}',0,'');
        load_drop_down('user_types','id,user_type_name','user_type','user_type_container','Select User Type',0,1,'{{$user_config_data['normal_user_type']}}',1,'');
        load_drop_down('departments','id,department_name','department','department_container','Select Department',0,0,'{{$user_data->department}}',0,'');
        load_drop_down('departments','id,department_name','assign_department','assign_department_container','Select Assign Department',1,0,'{{$user_data->assign_department}}',0,'');
        load_drop_down('designations','id,designation_name','designation','designation_container','Select Designation',0,0,'{{$user_data->designation}}',0,'');

    </script>
@endsection