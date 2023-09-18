<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">View User Information - {{$user_data->name}}</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!} 
                </div>
                <div class="card" style="margin: 0px !important;">
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-4"><h5>Name: {{$user_data->name}}</h5></div>
                            <div class="col-md-4"><h5>E-mail: {{$user_data->email}}</h5></div>
                            <div class="col-md-4"><h5>Mobile: {{$user_data->mobile}}</h5></div>
                            <div class="col-md-4"><h5>Date Of Birth: {{$user_data->date_of_birth}}</h5></div>
                            <div class="col-md-4"><h5 id="gender_div_id">Gender: </h5></div>
                            <div class="col-md-4"><h5 id="blood_group_div_id">Blood Group: </h5></div>
                            <div class="col-md-4"><h5 id="group_div_id">Group: </h5></div>
                            <div class="col-md-4"><h5 id="type_div_id">Type: {{$user_data->user_type}}</h5></div>
                            <div class="col-md-4"><h5 id="depertment_div_id">Depertment: </h5></div>
                            <div class="col-md-4"><h5 id="ass_depertment_div_id">Assign Depertment: {{$user_data->assign_department}}</h5></div>
                            <div class="col-md-4"><h5 id="designation_div_id">Designation: </h5></div>
                            <div class="col-md-4"><h5>Address: {{$user_data->address}}</h5></div>
                            <div class="col-md-4"><h5>Status: {{$user_data->status==1?'Active':'Inactive'}}</h5></div>
                            <div class="col-md-12"><h5>Details: {!!$user_data->details!!}</h5></div>
                            <div class="col-md-4"><h5>Image: <img class="rounded avatar-lg" width="80" height="80" id="group_logo_photo" src="{{asset('uploads/user')}}/{{!empty($user_data->user_photo)?$user_data->user_photo:'user.png'}}"/></h5></div>
                            <hr style="border: 1px solid white; width: 100%;">
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
        
        get_data_by_id('gender_div_id', '{{$user_data->sex}}', 'gender_name', 'genders');
        get_data_by_id('blood_group_div_id', '{{$user_data->blood_group}}', 'blood_group_name', 'blood_groups');
        get_data_by_id('group_div_id', '{{$user_data->group}}', 'group_name', 'user_groups');
        get_data_by_id('depertment_div_id', '{{$user_data->department}}', 'department_name', 'departments');
        get_data_by_id('designation_div_id', '{{$user_data->designation}}', 'designation_name', 'designations');
    </script>
    <!-- /.row -->
</div>****{{csrf_token()}}