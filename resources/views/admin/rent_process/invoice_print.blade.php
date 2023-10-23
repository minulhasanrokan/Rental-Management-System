<div class="invoice-box" id="PrintRentInvoice" style="background-image: url({{asset('backend/dist/img')}}/{{$rent_bill_data->paid_status==1?'paid.png':'due.png'}}); background-repeat: no-repeat; background-position: center; background-size: 100px 100px;">
      <table cellpadding="0" cellspacing="0">
        <tbody>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tbody>
                            <tr>
                                <td class="title" style="text-transform:uppercase;font-weight:bold;overflow:hidden;font-size:32px;">{{$building_data[0]->building_name}}<br>
                                    <img style="width:150" height="75" src="{{asset('uploads/building')}}/{{!empty($building_data[0]->building_logo)?$building_data[0]->building_logo:'building_logo.png'}}"></td>

                                <td>Invoice #{{$rent_bill_data->invoice_no}}<br>Issue Date: {{date('d-F-Y',strtotime($rent_bill_data->created_at))}}<br>Paid Status: {{$rent_bill_data->paid_status==1?'Paid':'Un-Paid'}}<br>Paid Date: {{date('d-F-Y',strtotime($rent_bill_data->payment_date))}}<br>Bill Month: {{$month_data[0]->name}}-{{$rent_bill_data->year_id}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tbody>
                            <tr>
                                <td>{{$system_data['system_name']}}<br>{{$building_data[0]->building_address}}<br>Mobile: {{$system_data['system_mobile']}}<br>Email: {{$system_data['system_email']}} </td>
                                <td>{{$user_data[0]->name}}<br>Floor: {{$level_data[0]->level_name}}({{$level_data[0]->level_code}}), Unit: {{$unit_data[0]->unit_name}}({{$unit_data[0]->unit_code}})<br>Mobile: {{$user_data[0]->mobile}}<br>E-mail: {{$user_data[0]->email}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>Bill Details </td>
                <td>Amount </td>
            </tr>
            <tr class="item">
                <td>House Rent</td>
                <td>{{$rent_bill_data->unit_rent}}</td>
            </tr>
            <tr class="item">
                <td>Water Bill</td>
                <td>{{$rent_bill_data->water_bill}}</td>
            </tr>
            <tr class="item">
                <td>Electricity Bill</td>
                <td>{{$rent_bill_data->electricity_bill}}</td>
            </tr>
            <tr class="item">
                <td>Gas Bill</td>
                <td>{{$rent_bill_data->gas_bill}}</td>
            </tr>
            <tr class="item">
                <td>Security Bill</td>
                <td>{{$rent_bill_data->security_bill}}</td>
            </tr>
            <tr class="item">
                <td>Maintenance Bill</td>
                <td>{{$rent_bill_data->maintenance_bill}}</td>
            </tr>
            <tr class="item">
                <td>Service Charge</td>
                <td>{{$rent_bill_data->service_bill}}</td>
            </tr>
            <tr class="item">
                <td>Charity Fund</td>
                <td>{{$rent_bill_data->charity_bill}}</td>
            </tr>
            <tr class="item">
                <td>Other Bill</td>
                <td>{{$rent_bill_data->other_bill}}</td>
            </tr>
            <tr class="item">
                <td>Discount</td>
                <td>{{$rent_bill_data->discount}}</td>
            </tr>
            <tr class="item">
                <td>Tax ({{$rent_bill_data->tax_amount}}%)</td>
                <td>
                    @php

                        $total_amount = ($rent_bill_data->unit_rent+$rent_bill_data->water_bill+$rent_bill_data->electricity_bill+$rent_bill_data->gas_bill+$rent_bill_data->security_bill+$rent_bill_data->maintenance_bill+$rent_bill_data->service_bill+$rent_bill_data->charity_bill+$rent_bill_data->other_bill-$rent_bill_data->discount);

                        echo $vat_amount = ($rent_bill_data->tax_amount / 100) * $total_amount;

                    @endphp
                </td>
            </tr>
            <tr class="item">
                <td>Total</td>
                <td>{{$rent_bill_data->total_amount}}</td>
            </tr>
        </tbody>
    </table>
    <div class="invoice-signature">
        <div>-------------------------</div>
        <div class="signature-text">Signature</div>
    </div>
</div>

<style>
    .signature-text{
        padding-right:5%;
    }
    .invoice-signature{
        text-align:right;
        margin-top:70px;
    }
    .bill-status-logo{
        padding-left:10%;
    }
    .invoice-box {
        max-width: 800px;
        margin: auto;
        font-size: 16px;
        line-height: 24px;
        color: #555;
        background:#fff;
        margin-top:35px;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 10px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
</style>