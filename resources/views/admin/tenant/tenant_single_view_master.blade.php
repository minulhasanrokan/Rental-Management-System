@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                    <div class="card-header">
                        <h3 class="card-title">View Tenant Information - {{$user_data->name}}</h3>
                    </div>
                    <div class="card-header" style="background-color: white;">
                        {!!$menu_data!!} 
                    </div>
                    <div class="card" style="margin: 0px !important;">
                        <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                            <div class="row">
                                <div class="col-md-3">
                                    <!-- Profile Image -->
                                    <div class="card card-primary card-outline">
                                        <div class="card-body box-profile">
                                            <div class="text-center">
                                                <img class="profile-user-img img-fluid img-circle" src="{{asset('uploads/user')}}/{{!empty($user_data->user_photo)?$user_data->user_photo:'user.png'}}" alt="{{$user_data->name}}">
                                            </div>
                                            <h3 class="profile-username text-center">{{$user_data->name}}</h3>
                                            <p class="text-muted text-center" style=" margin: 0px !important;"><a style="text-decoration: none; color:white;" href="mailto:{{$user_data->email}}">{{$user_data->email}}</a></p>
                                            <p class="text-muted text-center" style=" margin: 0px !important;"><a style="text-decoration: none; color:white;" href="tel:{{$user_data->mobile}}">{{$user_data->mobile}}</a></p>
                                            <p class="text-muted text-center" style="margin: 0px !important;" id="type_div_id"></p>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Address</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <p class="text-muted">{!!$user_data->address!!}</p>
                                        </div>
                                         <!-- /.card-body -->
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6"><p style="margin: 0px !important;">Date Of Birth: {{$user_data->date_of_birth}}</p></div>
                                        <div class="col-md-6"><p style="margin: 0px !important;" id="gender_div_id">Gender: </p></div>
                                        <div class="col-md-6"><p style="margin: 0px !important;" id="blood_group_div_id">Blood Group: </p></div>
                                        <div class="col-md-6"><p style="margin: 0px !important;" id="group_div_id">Group: </p></div>
                                        <div class="col-md-6"><p style="margin: 0px !important;" id="depertment_div_id">Depertment: </p></div>
                                        <div class="col-md-6"><p style="margin: 0px !important;" id="ass_depertment_div_id">Assign Depertment: </p></div>
                                        <div class="col-md-6"><p style="margin: 0px !important;" id="designation_div_id">Designation: </p></div>
                                        <div class="col-md-6"><p style="margin: 0px !important;">Status: {{$user_data->status==1?'Active':'Inactive'}}</p></div>
                                        <div class="col-md-12" style="margin-top: 10px;">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title">Details</h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body">
                                                    <p class="text-muted">{!!$user_data->details!!}</p>
                                                </div>
                                                 <!-- /.card-body -->
                                            </div>
                                        </div>
                                    </div>
                                </div>                            
                                <div class="col-md-12">
                                    @foreach($user_right_data as $data)
                                        <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$user_data->id}}','{{$user_data->name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </div>
        <script type="text/javascript">

            get_data_by_id('type_div_id', '{{$user_data->user_type}}', 'user_type_name', 'user_types');
            get_data_by_id('gender_div_id', '{{$user_data->sex}}', 'gender_name', 'genders');
            get_data_by_id('blood_group_div_id', '{{$user_data->blood_group}}', 'blood_group_name', 'blood_groups');
            get_data_by_id('group_div_id', '{{$user_data->group}}', 'group_name', 'user_groups');
            get_data_by_id('depertment_div_id', '{{$user_data->department}}', 'department_name', 'departments');
            get_data_by_id('ass_depertment_div_id', '{{$user_data->assign_department}}', 'department_name', 'departments');
            get_data_by_id('designation_div_id', '{{$user_data->designation}}', 'designation_name', 'designations');
        </script>
        <!-- /.row -->
    </div>
@endsection