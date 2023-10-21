<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Invoice Rent Information</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <div class="invoice p-3 mb-3">
                    <!-- info row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            From
                            <address>
                                <strong>{{$system_data['system_name']}}</strong><br>
                                {{$system_data['system_address']}}<br>
                                Phone: {{$system_data['system_mobile']}}<br>
                                Email: {{$system_data['system_email']}}
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            To
                            <address>
                                @foreach($user_data as $user)
                                <strong>{{$user->name}}</strong><br>
                                {{$user->address}}<br>
                                Phone: {{$user->mobile}}<br>
                                Email: {{$user->email}}
                                @endforeach
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <b>Invoice No:</b> #{{$rent_bill_data->invoice_no}}<br>
                            <b>Invoice Month:</b> {{$month_data[0]->name}}-{{$rent_bill_data->year_id}}<br>
                            <p style="margin: 0px !important; {{$rent_bill_data->paid_status==1?'':'color:red'}}"><b>Payment Status:</b> {{$rent_bill_data->paid_status==1?'Paid':'Un-Paid'}}</p>
                            <b>Payment Date:</b> {{$rent_bill_data->payment_date}}<br>
                            <b>Unit Name:</b> {{$building_data[0]->building_name}}({{$building_data[0]->building_code}})-{{$level_data[0]->level_name}}({{$level_data[0]->level_code}})-{{$unit_data[0]->unit_name}}({{$unit_data[0]->unit_code}})
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <!-- Table row -->
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Rent Particular</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>01</td>
                                        <td>House Rent</td>
                                        <td>{{$rent_bill_data->unit_rent}}</td>
                                    </tr>
                                    <tr>
                                        <td>02</td>
                                        <td>Water Bill</td>
                                        <td>{{$rent_bill_data->water_bill}}</td>
                                    </tr>
                                    <tr>
                                        <td>03</td>
                                        <td>Electricity Bill</td>
                                        <td>{{$rent_bill_data->electricity_bill}}</td>
                                    </tr>
                                    <tr>
                                        <td>04</td>
                                        <td>Gas Bill</td>
                                        <td>{{$rent_bill_data->gas_bill}}</td>
                                    </tr>
                                    <tr>
                                        <td>05</td>
                                        <td>Security Bill</td>
                                        <td>{{$rent_bill_data->security_bill}}</td>
                                    </tr>
                                    <tr>
                                        <td>06</td>
                                        <td>Maintenance Bill</td>
                                        <td>{{$rent_bill_data->maintenance_bill}}</td>
                                    </tr>
                                    <tr>
                                        <td>07</td>
                                        <td>Service Charge</td>
                                        <td>{{$rent_bill_data->service_bill}}</td>
                                    </tr>
                                    <tr>
                                        <td>08</td>
                                        <td>Charity Fund</td>
                                        <td>{{$rent_bill_data->charity_bill}}</td>
                                    </tr>
                                    <tr>
                                        <td>09</td>
                                        <td>Other Bill</td>
                                        <td>{{$rent_bill_data->other_bill}}</td>
                                    </tr>
                                    <tr>
                                        <td>10</td>
                                        <td>Discount</td>
                                        <td>{{$rent_bill_data->discount}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-6">
                    </div>
                    <div class="col-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th style="width:50%">Subtotal:</th>
                                    <td>{{$rent_bill_data->unit_rent+$rent_bill_data->water_bill+$rent_bill_data->electricity_bill+$rent_bill_data->gas_bill+$rent_bill_data->security_bill+$rent_bill_data->maintenance_bill+$rent_bill_data->service_bill+$rent_bill_data->charity_bill+$rent_bill_data->other_bill-$rent_bill_data->discount}}</td>
                                </tr>
                                <tr>
                                    <th>Tax (9.3%)</th>
                                    <td>$10.34</td>
                                </tr>
                                <tr>
                                    <th>Total:</th>
                                    <td>$265.24</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row no-print">
                    <div class="col-12">
                        @foreach($user_right_data as $data)
                            <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$rent_bill_data->id}}','{{$rent_bill_data->unit_rent}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
                        @endforeach
                        <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                            <i class="fas fa-download"></i> Generate PDF
                        </button>
                        <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>****{{csrf_token()}}