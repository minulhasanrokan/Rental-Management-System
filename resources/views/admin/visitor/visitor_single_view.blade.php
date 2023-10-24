<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">View Visitor Information ({{$visitor_data->visitor_name}})</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!} 
                </div>
                <div class="card" style="margin: 0px !important;">
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr>
                                        <td style="width:200px;" style="text-align:left;">Visitor Name:</td>
                                        <td style="text-align:left;">{{$visitor_data->visitor_name}}</td>
                                        <td style="width:200px;" style="text-align:left;">Visitor Mobile:</td>
                                        <td style="text-align:left;">{{$visitor_data->visitor_mobile}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:200px;" style="text-align:left;">Visitor Address:</td>
                                        <td style="text-align:left;">{{$visitor_data->visitor_address}}</td>
                                        <td style="width:200px;" style="text-align:left;">Visit Reason:</td>
                                        <td style="text-align:left;">{{$visitor_data->visitor_reason}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:200px;" style="text-align:left;">Building Name:</td>
                                        <td style="text-align:left;" id="building_div_id"></td>
                                         <td style="width:200px;" style="text-align:left;">Level Name:</td>
                                        <td style="text-align:left;" id="level_div_id"></td>
                                    </tr>
                                    <tr>
                                        <td style="width:200px;" style="text-align:left;">Unit Name:</td>
                                        <td style="text-align:left;" id="unit_div_id"></td>
                                        <td style="width:200px;" style="text-align:left;">Tenant Name:</td>
                                        <td style="text-align:left;" id="tenant_div_id"></td>
                                    </tr>
                                    <tr>
                                        <td style="width:200px;" style="text-align:left;">Entry Time:</td>
                                        <td style="text-align:left;">{{$visitor_data->entry_date}} {{$visitor_data->entry_time}}</td>
                                        <td style="width:200px;" style="text-align:left;">Out Time:</td>
                                        <td style="text-align:left;">{{$visitor_data->out_date}} {{$visitor_data->out_time}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-12">
                                @foreach($user_right_data as $data)
                                    <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$visitor_data->id}}','{{$visitor_data->visitor_name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
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
        get_data_by_id('tenant_div_id', '{{$visitor_data->tenant_id}}', 'name', 'users');
        get_data_by_id('building_div_id', '{{$visitor_data->building_id}}', 'building_name', 'buildings');
        get_data_by_id('level_div_id', '{{$visitor_data->level_id}}', 'level_name', 'levels');
        get_data_by_id('unit_div_id', '{{$visitor_data->unit_id}}', 'unit_name', 'units');
    </script>
</div>****{{csrf_token()}}