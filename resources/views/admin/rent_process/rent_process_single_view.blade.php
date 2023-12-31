<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">View Rent Information</h3>
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
                                        <td id="tenant_div_id" colspan="2" style="text-align:left;">Tenant Name: </td>
                                        <td id="month_div_id" colspan="2" style="text-align:left;">Bill Month: </td>
                                        <td id="year_div_id" colspan="2" style="text-align:left;">Bill Year: {{$rent_bill_data->year_id}}</td>
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
                                        <td style="text-align:left;">Rent:</td>
                                        <td style="text-align:right;">{{$rent_bill_data->unit_rent}}</td>
                                        <td style="text-align:left;">Water Bill:</td>
                                        <td style="text-align:right;">{{$rent_bill_data->water_bill}}</td>
                                        <td style="text-align:left;">Electricity Bill:</td>
                                        <td style="text-align:right;">{{$rent_bill_data->electricity_bill}}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;">Gas Bill:</td>
                                        <td style="text-align:right;">{{$rent_bill_data->gas_bill}}</td>
                                        <td style="text-align:left;">Security Bill</td>
                                        <td style="text-align:right;">{{$rent_bill_data->security_bill}}</td>
                                        <td style="text-align:left;">Maintenance Bill:</td>
                                        <td style="text-align:right;">{{$rent_bill_data->maintenance_bill}}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;">Service Charge:</td>
                                        <td style="text-align:right;">{{$rent_bill_data->service_bill}}</td>
                                        <td style="text-align:left;">Charity Fund:</td>
                                        <td style="text-align:right;">{{$rent_bill_data->charity_bill}}</td>
                                        <td style="text-align:left;">Other Bill:</td>
                                        <td style="text-align:right;">{{$rent_bill_data->other_bill}}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;">Discount:</td>
                                        <td style="text-align:right;">{{$rent_bill_data->discount}}</td>
                                         <td style="text-align:left;">Vat-Tax:</td>
                                        <td style="text-align:right;">{{$rent_bill_data->tax_amount}}(%)</td>
                                        
                                        <td style="text-align:left;">Total Bill:</td>
                                        <td style="text-align:right;">{{$rent_bill_data->total_amount}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-12">
                                @foreach($user_right_data as $data)
                                    <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$encrypt_id}}','{{$rent_bill_data->unit_rent}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
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
        get_data_by_id('tenant_div_id', '{{$rent_bill_data->tenant_id}}', 'name', 'users');
        get_data_by_id('month_div_id', '{{$rent_bill_data->month_id}}', 'name', 'months');
        get_data_by_id('building_div_id', '{{$rent_bill_data->building_id}}', 'building_name', 'buildings');
        get_data_by_id('level_div_id', '{{$rent_bill_data->level_id}}', 'level_name', 'levels');
        get_data_by_id('unit_div_id', '{{$rent_bill_data->unit_id}}', 'unit_name', 'units');
    </script>
</div>****{{csrf_token()}}