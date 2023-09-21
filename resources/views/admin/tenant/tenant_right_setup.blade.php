<div class="container-fluid" style="padding-top: 5px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                <div class="card-header">
                    <h3 class="card-title">Set Tenant Right - {{$user_data->name}}</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="user_right_form" method="post" autocomplete="off">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id" value="{{$user_data->id}}" />
                    <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                        <div class="row">
                            @php
                                if(isset($all_right_data['right_group_arr']) && !empty($all_right_data['right_group_arr'])){

                                    $i=0;

                                    foreach($all_right_data['right_group_arr'] as $right_group){

                                        $i++;
                            @endphp
                                        <div class="col-md-12">
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">
                                                    <label class="control-label input-label" for="g_id_checkbox_{{$i}}">{{$right_group['g_name']}} </label>
                                                    <input type="checkbox" value="0" id="g_id_checkbox_{{$i}}" name="g_id_checkbox_{{$i}}" onclick="select_all_group_right({{$i}});" />
                                                    <input type="hidden" id="g_id_{{$i}}" name="g_id_{{$i}}" value="{{$right_group['g_id']}}" />
                                                </legend>
                                                <div class="row">
                                                    @php
                                                        if(isset($all_right_data['right_cat_arr'][$right_group['g_id']]) && !empty($all_right_data['right_cat_arr'][$right_group['g_id']])){

                                                            $j = 0;

                                                            foreach($all_right_data['right_cat_arr'][$right_group['g_id']] as $right_cat){

                                                                $j++;

                                                    @endphp     
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <h4>
                                                                            <label class="control-label input-label" for="c_id_checkbox_{{$i}}_{{$j}}">{{$right_cat['c_name']}} </label>
                                                                            <input type="checkbox" value="0" id="c_id_checkbox_{{$i}}_{{$j}}" name="c_id_checkbox_{{$i}}_{{$j}}" onclick="select_all_cat_right({{$i}},{{$j}});" />
                                                                            <input type="hidden" id="c_id_{{$i}}_{{$j}}" name="c_id_{{$i}}_{{$j}}" value="{{$right_cat['c_id']}}" />
                                                                        </h5>
                                                                        @php
                                                                            if(isset($all_right_data['right_arr'][$right_group['g_id']][$right_cat['c_id']]) && !empty($all_right_data['right_arr'][$right_group['g_id']][$right_cat['c_id']])){

                                                                                $k=0;
                                                                                $status = 1;

                                                                                foreach($all_right_data['right_arr'][$right_group['g_id']][$right_cat['c_id']] as $right){

                                                                                    $checked= "";
                                                                                    $value = 0;

                                                                                    if(isset($right_data[$right_group['g_id']][$right_cat['c_id']][$right['r_id']]['r_id'])){

                                                                                        $checked ="checked";

                                                                                        $value = 1;
                                                                                    }
                                                                                    else{

                                                                                        $status = 0;
                                                                                    }

                                                                                    $k++;
                                                                        @endphp
                                                                                    <input {{$checked}} type="checkbox" value="{{$value}}" id="r_id_checkbox_{{$i}}_{{$j}}_{{$k}}" name="r_id_checkbox_{{$i}}_{{$j}}_{{$k}}" onclick="select_right({{$i}},{{$j}},{{$k}});" />
                                                                                    <input type="hidden" id="r_id_{{$i}}_{{$j}}_{{$k}}" name="r_id_{{$i}}_{{$j}}_{{$k}}" value="{{$right['r_id']}}" />
                                                                                    <label class="control-label input-label" for="r_id_checkbox_{{$i}}_{{$j}}_{{$k}}">{{$right['r_name']}} <i class="fa {{$right['r_icon']}}"></i></label>
                                                                                    <br>
                                                                        @php
                                                                                }

                                                                                if($status==1){

                                                                                    @endphp
                                                                                        <script type="text/javascript">
                                                                                            select_right({{$i}},{{$j}},{{$k}});
                                                                                        </script>
                                                                                    @php
                                                                                }

                                                                                @endphp

                                                                                    <input type="hidden" id="r_id_max_{{$i}}_{{$j}}" name="r_id_max_{{$i}}_{{$j}}" value="{{$k}}" />
                                                                                @php
                                                                            }
                                                                        @endphp
                                                                    </div>
                                                                </div>
                                                                
                                                    @php
                                                            }

                                                            @endphp

                                                                <input type="hidden" id="c_id_max_{{$i}}" name="c_id_max_{{$i}}" value="{{$j}}" />
                                                            @php
                                                        }
                                                    @endphp
                                                </div>
                                            </fieldset>
                                        </div>
                            @php
                                    }

                                    @endphp

                                        <input type="hidden" id="g_id_max" name="g_id_max" value="{{$i}}" />
                                    @php
                                }
                            @endphp
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @foreach($user_right_data as $data)
                            <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$user_data->id}}','{{$user_data->name}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
                        @endforeach
                        <button type="button" style="float:right" onclick="save_user_right();" class="btn btn-primary">Set Tenant Right</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
        <!--/.col (left) -->
    </div>
    <!-- /.row -->
</div>
<style type="text/css">
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding-left: 10px !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        width:inherit; /* Or auto */
        padding:0 5px; /* To give a bit of padding on the left and right */
        border-bottom:none;
    }
</style>

<script>

    function select_all_group_right(id){

        var status = false;

        if ($('#g_id_checkbox_'+id).prop('checked')) {
            
            status = true;
            $('#g_id_checkbox_'+id).val(1);
        }
        else{

            $('#g_id_checkbox_'+id).val(0);
        }

        var max_c_id_sl = $("#c_id_max_"+id).val();

        for(var i=1; i<=max_c_id_sl; i++){

            var max_r_id_sl = $("#r_id_max_"+id+"_"+i).val();

            if(status==true){

                $('#c_id_checkbox_'+id+"_"+i).prop('checked', true);
                $('#c_id_checkbox_'+id+"_"+i).val(1);
            }
            else{

                $('#c_id_checkbox_'+id+"_"+i).prop('checked', false);
                $('#c_id_checkbox_'+id+"_"+i).val(0);
            }

            for(var j=1; j<=max_r_id_sl; j++){

                if(status==true){

                    $('#r_id_checkbox_'+id+"_"+i+"_"+j).prop('checked', true);
                    $('#r_id_checkbox_'+id+"_"+i+"_"+j).val(1);
                }
                else{

                    $('#r_id_checkbox_'+id+"_"+i+"_"+j).prop('checked', false);
                    $('#r_id_checkbox_'+id+"_"+i+"_"+j).val(0);
                }
            }
        }
    }

    function select_all_cat_right(g_id,cat_id){

        var status = false;

        if ($('#c_id_checkbox_'+g_id+"_"+cat_id).prop('checked')) {
            
            status = true;

            $('#c_id_checkbox_'+g_id+"_"+cat_id).val(1);
        }
        else{

            $('#g_id_checkbox_'+g_id).prop('checked', false);
            $('#g_id_checkbox_'+g_id).val(0);
            $('#c_id_checkbox_'+g_id+"_"+cat_id).val(0);
        }

        var max_r_id_sl = $("#r_id_max_"+g_id+"_"+cat_id).val();

        for(var j=1; j<=max_r_id_sl; j++){

            if(status==true){

                $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+j).prop('checked', true);
                $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+j).val(1);
            }
            else{

                $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+j).prop('checked', false);
                $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+j).val(0);
            }
        }

        check_group(g_id);
    }

    function select_right(g_id,cat_id,r_id){

        if ($('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+r_id).prop('checked')) {

            $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+r_id).val(1);

            var max_r_id_sl = $("#r_id_max_"+g_id+"_"+cat_id).val();

            var status = false;

            for(var j=1; j<=max_r_id_sl; j++){

                if ($('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+j).prop('checked')) {

                    $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+j).val(1);

                    status = true;
                }
                else{

                    $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+j).val(0);

                    status = false;
                    break;
                }
            }

            if(status==true){

                $('#c_id_checkbox_'+g_id+"_"+cat_id).prop('checked', true);
                $('#c_id_checkbox_'+g_id+"_"+cat_id).val(1);

                check_group(g_id);
            }
            else{

                $('#c_id_checkbox_'+g_id+"_"+cat_id).prop('checked', false);
                $('#c_id_checkbox_'+g_id+"_"+cat_id).val(0);

                check_group(g_id);
            }
        }
        else{

            $('#c_id_checkbox_'+g_id+"_"+cat_id).prop('checked', false);
            $('#c_id_checkbox_'+g_id+"_"+cat_id).val(0);
            $('#g_id_checkbox_'+g_id).prop('checked', false);
            $('#g_id_checkbox_'+g_id).val(0);
            $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+r_id).val(0);
        }
    }

    function check_group(g_id){

        var max_c_id_sl = $("#c_id_max_"+g_id).val();

        var status = false;

        for(var i=1; i<=max_c_id_sl; i++){

            if ($('#c_id_checkbox_'+g_id+"_"+i).prop('checked')) {

                status = true;
                $('#c_id_checkbox_'+g_id+"_"+i).val(1);
            }
            else{

                $('#c_id_checkbox_'+g_id+"_"+i).val(0);

                status = false;
                break;
            }
        }

        if(status==true){

            $('#g_id_checkbox_'+g_id).prop('checked', true);
            $('#g_id_checkbox_'+g_id).val(1);
        }
        else{

            $('#g_id_checkbox_'+g_id).prop('checked', false);
            $('#g_id_checkbox_'+g_id).val(0);
        }
    }

    function save_user_right(){

        var token = $('meta[name="csrf-token"]').attr('content');

        var form_data = new FormData();

        var max_g = $("#g_id_max").val();
        var user_id = $("#user_id").val();

        form_data.append("g_id_max", max_g);

        for(var i=1; i<=max_g; i++){

            form_data.append("g_id_checkbox_"+i, $("#g_id_checkbox_"+i).val());
            form_data.append("g_id_"+i, $("#g_id_"+i).val());
            form_data.append("c_id_max_"+i, $("#c_id_max_"+i).val());

            var c_id_max = $("#c_id_max_"+i).val();

            for(var j=1; j<=c_id_max; j++){

                form_data.append("c_id_checkbox_"+i+"_"+j, $("#c_id_checkbox_"+i+"_"+j).val());
                form_data.append("c_id_"+i+"_"+j, $("#c_id_"+i+"_"+j).val());
                form_data.append("r_id_max_"+i+"_"+j, $("#r_id_max_"+i+"_"+j).val());

                var r_id_max = $("#r_id_max_"+i+"_"+j).val();

                for(var k=1; k<=r_id_max; k++){

                    form_data.append("r_id_checkbox_"+i+"_"+j+"_"+k, $("#r_id_checkbox_"+i+"_"+j+"_"+k).val());
                    form_data.append("r_id_"+i+"_"+j+"_"+k, $("#r_id_"+i+"_"+j+"_"+k).val());
                }
            }
        }

        form_data.append("_token", token);
        form_data.append("user_id", user_id);

        http.open("POST","{{route('user_management.tenant.right',$user_data->id)}}",true);
        http.setRequestHeader("X-CSRF-TOKEN",token);
        http.send(form_data);
        http.onreadystatechange = save_user_right_response;
    }

    function save_user_right_response(){

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

</script>****{{csrf_token()}}