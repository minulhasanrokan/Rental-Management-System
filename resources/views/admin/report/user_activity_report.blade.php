<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">User Activity Report</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="complain_form" method="post" autocomplete="off">
                    @csrf
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="group_id">Activity Group <span style="color:red;">*</span></label>
                                    <div id="group_id_container"></div>
                                    <div class="input-error" style="display:none; color: red;" id="group_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="category_id">Activity Category <span style="color:red;">*</span></label>
                                    <div id="category_id_container">
                                        <select class="form-control select" style="width: 100%;" name="category_id" id="category_id">
                                            <option value="">Select Activity Category</option>
                                        </select>
                                    </div>
                                    <div class="input-error" style="display:none; color: red;" id="category_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="right_id">Activity <span style="color:red;">*</span></label>
                                    <div id="right_id_container">
                                        <select class="form-control select" style="width: 100%; float: left;" name="right_id" id="right_id">
                                            <option value="">Select Activity</option>
                                        </select>
                                    </div>
                                    <div class="input-error" style="display:none; color: red;" id="right_id_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="from_date">From Data<span style="color:red;">*</span></label>
                                    <input type="date" class="form-control" id="from_date" name="from_date" placeholder="Enter From Date" required>
                                    <div class="input-error" style="display:none; color: red;" id="from_date_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="to_date">To Data<span style="color:red;">*</span></label>
                                    <input type="date" class="form-control" id="to_date" name="to_date" placeholder="Enter To Date" required>
                                    <div class="input-error" style="display:none; color: red;" id="to_date_error" style="display: inline-block; width:100%; color: red;"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="search_button_id">&nbsp;</label>
                                    <div>
                                        <button type="button" id="search_button_id" style="float:right; width:100%;" onclick="get_report();" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card" style="margin: 0px !important;">
                    <div id="report_data" class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!--/.col (left) -->
    </div>
    <!-- /.row -->
</div>

<script>

    $(function () {

        $('#complain_details').summernote({
            height: 150,
            focus: true
        })
    });

    function get_report(){

        if( form_validation('group_id*category_id*right_id*from_date*to_date','Activity Group*Activity Category*Activity Name*From Date*To Date')==false ){

            return false;
        }

        var group_id = $("#group_id").val();
        var category_id = $("#category_id").val();
        var right_id = $("#right_id").val();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();

        if (from_date > to_date) {
            
            alert('From date Can Not Be Gratter Than To Date');

            return false;
        }

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();
        
        form_data.append("group_id", group_id);
        form_data.append("category_id", category_id);
        form_data.append("right_id", right_id);
        form_data.append("from_date", from_date);
        form_data.append("to_date", to_date);

        form_data.append("_token", token);

        freeze_window(0);

        http.open("POST","{{route('report.system_report.user_activity_report')}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = get_report_response;
    }

    function get_report_response(){

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

                    var objectLength = Object.keys(data.report_data).length;

                    if(objectLength>0){

                        var data_table ='<table id="report_data_table" class="table table-bordered table-striped" style="text-align: center !important; vertical-align: middle !important;" rules="all"><thead><tr><th width="20">Sl</th><th width="100">Group Name</th><th width="130">Category Name</th><th width="200">Activity</th><th width="150">User Name</th><th width="10">Action</th></tr>';

                        var j=1;

                        for (var i = 0; i<objectLength; i++) {
                            
                            data_table +='<tr><td>'+j+'</td><td>'+data.report_data[i]['group_name']+'</td><td>'+data.report_data[i]['category_name']+'</td><td>'+data.report_data[i]['right_name']+'</td><td>'+data.report_data[i]['user_name']+'</td></tr>';

                            j++;
                        }

                        data_table +='</thead></table>';

                        $("#report_data").html(data_table);
                    }
                    else{

                        alert("No Data Found");

                        $("#report_data").html('No Data Found');
                    }
                }

                // hide all input error.............
                $(".input-error").delay(3000).fadeOut(800);
            }
        }
    }

    load_drop_down('right_groups','id,name','group_id','group_id_container','Select Activity Group',0,1,'',0,'onchange="load_drop_down_by_id(\'right_categories\',\'id,c_name\',\'category_id\',\'category_id_container\',\'Select Activity Category\',0,1,\'\',0,this.value,\'group_id\',\'onchange=get_unit_load_drop_down_by_id(this.value)\',\'\',\'\')"');

    function get_unit_load_drop_down_by_id(value){

        load_drop_down_by_id('right_details','id,r_name','right_id','right_id_container','Select Activity',0,1,'',0,value,'cat_id','','','');
    }

</script>****{{csrf_token()}}