<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">View Level Information - {{$level_data->level_name}}</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!} 
                </div>
                <div class="card" style="margin: 0px !important;">
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-6"><p style="margin: 0px !important;" id="building_div_id">Building Name: </p></div>
                            <div class="col-md-6"><p>Level Name: {{$level_data->level_name}}</p></div>
                            <div class="col-md-6"><p>Level Code: {{$level_data->level_code}}</p></div>
                            <div class="col-md-6"><p>Level Title: {{$level_data->level_title}}</p></div>
                            <div class="col-md-6"><p>Level Status: {{$level_data->status==1?'Active':'Inactive'}}</p></div>
                            <div class="col-md-12"><h3>Level Details:</h3>{!!$level_data->level_deatils!!}</div>
                            <hr style="border: 1px solid white; width: 100%;">
                            <div class="col-md-12">
                                @foreach($user_right_data as $data)
                                    <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$level_data->id}}','{{$level_data->level_name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
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
    <!-- /.row -->
    <script type="text/javascript">
        get_data_by_id('building_div_id', '{{$level_data->building_id}}', 'building_name', 'buildings');
    </script>
</div>****{{csrf_token()}}