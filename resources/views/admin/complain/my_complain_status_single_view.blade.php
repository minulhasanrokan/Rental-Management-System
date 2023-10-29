<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">View Complain Details Information</h3>
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
                                        <td id="tenant_div_id" colspan="6" style="text-align:center;">Tenant Name: </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;">Building Name:</td>
                                        <td style="text-align:left;" id="building_div_id"></td>
                                        <td style="text-align:left;">lavel Name:</td>
                                        <td style="text-align:left;" id="level_div_id"></td>
                                        <td style="text-align:left;">Unit Name:</td>
                                        <td style="text-align:left;" id="unit_div_id"></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;">Complain Title:</td>
                                        <td colspan="5" style="text-align:left;">{{$complain_data->complain_title}}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;">Complain Details:</td>
                                        <td colspan="5" style="text-align:left;">{!!$complain_data->complain_details!!}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;">Assign User:</td>
                                        <td colspan="2" style="text-align:left;" id="user_div_id"></td>
                                        <td style="text-align:left;">Process Status:</td>
                                        <td colspan="2" style="text-align:left;">
                                            @php
                                                if($complain_data->process_status=='0'){
                                                    
                                                    echo "Panding";
                                                }
                                                else if($complain_data->process_status=='1'){

                                                    echo "In progress";
                                                }
                                                else if($complain_data->process_status=='2'){

                                                    echo "On hold";
                                                }
                                                else if($complain_data->process_status=='3'){

                                                    echo "Completed";
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;">Complain Remarks:</td>
                                        <td colspan="5" style="text-align:left;">{!!$complain_data->complain_remarks!!}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;">Tenant Remarks:</td>
                                        <td colspan="5" style="text-align:left;">{!!$complain_data->tenant_remarks!!}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-12">
                                @foreach($user_right_data as $data)
                                    <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$complain_data->id}}','{{$complain_data->unit_rent}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
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
        get_data_by_id('tenant_div_id', '{{$complain_data->tenant_id}}', 'name', 'users');
        get_data_by_id('building_div_id', '{{$complain_data->building_id}}', 'building_name', 'buildings');
        get_data_by_id('level_div_id', '{{$complain_data->level_id}}', 'level_name', 'levels');
        get_data_by_id('unit_div_id', '{{$complain_data->unit_id}}', 'unit_name', 'units');
        get_data_by_id('user_div_id', '{{$complain_data->assign_user}}', 'name', 'users');
    </script>
</div>****{{csrf_token()}}