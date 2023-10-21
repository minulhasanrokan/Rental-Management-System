<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Edit Vat Tax Information</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="vat_tax_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="year_id">Year <span style="color:red;">*</span></label>
                                    <div id="year_id_container">
                                        <select class="form-control select" style="width: 100%;" onchange="blank_month();" name="year_id" id="year_id">
                                            <option value="">Select Year</option>
                                        </select>
                                    </div>
                                    <div class="input-error" style="display:none; color: red;" id="year_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="month_id">Month <span style="color:red;">*</span></label>
                                    <div id="month_id_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="month_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tax_amount">Vat Tax Amount(%) <span style="color:red;">*</span></label>
                                    <input type="text" onchange="check_vat_amount()" class="form-control text_boxes_numeric" id="tax_amount" name="tax_amount" value="{{$tax_data->tax_amount}}" placeholder="Enter Vat Tax Amount" required>
                                    <div class="input-error" style="display:none; color: red;" id="tax_amount_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="button" style="float:right" onclick="save_vat_tax_info_data();" class="btn btn-primary">Update Vat Tax Information</button>
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

    function blank_month(){

        $("#month_id").val('');
    }

    function check_vat_amount(){

        var tax_amount = $("#tax_amount").val();

        if(tax_amount*1>100){

            alert("Vat Tax Amount Can Not Be Grater Then 100");

            $("#tax_amount").val('');

            return false;
        }
    }

    function save_vat_tax_info_data(){

        if( form_validation('year_id*month_id*tax_amount','Year*Month Name*Vat Tax Amount')==false ){

            return false;
        }

        var year_id = $("#year_id").val();
        var month_id = $("#month_id").val();
        var tax_amount = $("#tax_amount").val();

        if(tax_amount*1>100){

            alert("Vat Tax Amount Can Not Be Grater Then 100");

            $("#tax_amount").val('');

            return false;
        }

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        form_data.append("year_id", year_id);
        form_data.append("month_id", month_id);
        form_data.append("tax_amount", tax_amount);
        form_data.append("update_id", '{{$tax_data->id}}');
        form_data.append("_token", token);

        http.open("POST","{{route('reference_data.tax.edit',$tax_data->id)}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_vat_tax_info_data_response;
    }

    function save_vat_tax_info_data_response(){

        if(http.readyState == 4)
        {
            if(http.responseText=='Session Expire' || http.responseText=='Right Not Found'){

                alert('Session Expire');

                location.replace('<?php echo url('/dashboard/logout');?>');
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
                    //$(".input-error").delay(3000).fadeOut(800);
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
        }
    }

    load_drop_down('months','id,name','month_id','month_id_container','Select Month',0,1,'{{$tax_data->month_id}}',0,'onchange=\"check_duplicate_value_with_two_filed(\'month_id\',\'year_id\',\'vat_taxes\',this.value,\'{{$tax_data->id}}\',\'\',\'\');\"');

    var dateDropdown = document.getElementById('year_id');

    var currentYear = new Date().getFullYear();
    var earliestYear = 2000;

    while (currentYear >= earliestYear) {

        var dateOption = document.createElement('option');
        dateOption.text = currentYear;
        dateOption.value = currentYear;
        dateDropdown.add(dateOption);
        currentYear -= 1;
    }

    $("#year_id").val('{{$tax_data->year_id}}');

</script>****{{csrf_token()}}