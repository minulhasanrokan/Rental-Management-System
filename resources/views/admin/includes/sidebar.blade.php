@php

    $admin_status = Session::get(config('app.app_session_name').'.super_admin_status');
    $user_id = Session::get(config('app.app_session_name').'.id');
    $user_name = Session::get(config('app.app_session_name').'.name');
    $user_photo = Session::get(config('app.app_session_name').'.user_photo');

    $user_right_data = array();

    if($admin_status==1)
    {
        $user_right_data = DB::table('right_details')
                ->join('right_categories', 'right_categories.id', '=', 'right_details.cat_id')
                ->join('right_groups', 'right_groups.id', '=', 'right_categories.group_id')
                ->select('right_groups.id as g_id', 'right_groups.name as g_name', 'right_groups.action_name as g_action_name', 'right_groups.details as g_details', 'right_groups.title as g_title', 'right_groups.short_order as g_short_order', 'right_groups.icon as g_icon', 'right_categories.id as c_id', 'right_categories.group_id as group_id', 'right_categories.c_name', 'right_categories.c_title', 'right_categories.c_action_name', 'right_categories.c_details', 'right_categories.short_order as c_short_order', 'right_categories.c_icon as c_icon', 'right_details.id  as r_id', 'right_details.cat_id', 'right_details.r_name', 'right_details.r_title', 'right_details.r_action_name', 'right_details.r_route_name', 'right_details.r_details', 'right_details.r_short_order', 'right_details.r_icon')
                ->where('right_groups.status',1)
                ->where('right_categories.status',1)
                ->where('right_details.status',1)
                ->where('right_groups.delete_status',0)
                ->where('right_categories.delete_status',0)
                ->where('right_details.delete_status',0)
                ->orderBy('right_groups.short_order', 'ASC')
                ->orderBy('right_categories.short_order', 'ASC')
                ->orderBy('right_details.r_short_order', 'ASC')
                ->get()->toArray();
    }
    else{

        $user_right_data = DB::table('right_details')
                ->join('user_rights', 'right_details.id', '=', 'user_rights.r_id')
                ->join('right_categories', 'right_categories.id', '=', 'right_details.cat_id')
                ->join('right_groups', 'right_groups.id', '=', 'right_categories.group_id')
                ->select('right_groups.id as g_id', 'right_groups.name as g_name', 'right_groups.action_name as g_action_name', 'right_groups.details as g_details', 'right_groups.title as g_title', 'right_groups.short_order as g_short_order', 'right_groups.icon as g_icon', 'right_categories.id as c_id', 'right_categories.group_id as group_id', 'right_categories.c_name', 'right_categories.c_title', 'right_categories.c_action_name', 'right_categories.c_details', 'right_categories.short_order as c_short_order', 'right_categories.c_icon as c_icon', 'right_details.id  as r_id', 'right_details.cat_id', 'right_details.r_name', 'right_details.r_title', 'right_details.r_action_name', 'right_details.r_route_name', 'right_details.r_details', 'right_details.r_short_order', 'right_details.r_icon')
                ->where('user_rights.user_id',$user_id)
                ->where('right_groups.status',1)
                ->where('right_categories.status',1)
                ->where('right_details.status',1)
                ->where('right_groups.delete_status',0)
                ->where('right_categories.delete_status',0)
                ->where('right_details.delete_status',0)
                ->orderBy('right_groups.short_order', 'ASC')
                ->orderBy('right_categories.short_order', 'ASC')
                ->orderBy('right_details.r_short_order', 'ASC')
                ->get()->toArray();
    }

    $right_group_arr = array();
    $right_cat_arr = array();
    $right_arr = array();

    if(!empty($user_right_data)){

        foreach($user_right_data as $data){

            $right_group_arr[$data->g_id]['g_id'] = $data->g_id;
            $right_group_arr[$data->g_id]['g_name'] = $data->g_name;
            $right_group_arr[$data->g_id]['g_action_name'] = $data->g_action_name;
            $right_group_arr[$data->g_id]['g_details'] = $data->g_details;
            $right_group_arr[$data->g_id]['g_title'] = $data->g_title;
            $right_group_arr[$data->g_id]['g_short_order'] = $data->g_short_order;
            $right_group_arr[$data->g_id]['g_icon'] = $data->g_icon;

            $right_cat_arr[$data->g_id][$data->c_id]['c_id'] = $data->c_id;
            $right_cat_arr[$data->g_id][$data->c_id]['c_name'] = $data->c_name;
            $right_cat_arr[$data->g_id][$data->c_id]['c_title'] = $data->c_title;
            $right_cat_arr[$data->g_id][$data->c_id]['c_action_name'] = $data->c_action_name;
            $right_cat_arr[$data->g_id][$data->c_id]['c_details'] = $data->c_details;
            $right_cat_arr[$data->g_id][$data->c_id]['c_short_order'] = $data->c_short_order;
            $right_cat_arr[$data->g_id][$data->c_id]['c_icon'] = $data->c_icon;

            $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_id'] = $data->r_id;
            $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_name'] = $data->r_name;
            $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_title'] = $data->r_title;
            $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_action_name'] = $data->r_action_name;
            $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_route_name'] = $data->r_route_name;
            $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_details'] = $data->r_details;
            $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_short_order'] = $data->r_short_order;
            $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_icon'] = $data->r_icon;
        }
    }

@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('admin.dashboard')}}" class="brand-link">
        <img src="{{asset('uploads/logo')}}/{{!empty($system_data['system_logo'])?$system_data['system_logo']:'rental_logo.png'}}" alt="{{!empty($system_data['system_name'])?$system_data['system_name']:'Rental Management System'}}" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{!empty($system_data['system_name'])?$system_data['system_name']:'Rental Management System'}}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('uploads/user')}}/{{!empty($user_photo)?$user_photo:'user.png'}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{route('admin.dashboard')}}" class="d-block">{{$user_name}}</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @php
                    $group_menu_sl= 0;

                    foreach($right_group_arr as $group_data){

                        $group_menu_sl++;
                @endphp
                    <li class="nav-item" id="menu_group{{$group_menu_sl}}" onclick="group_menu_open('{{$group_menu_sl}}');">
                        <a href="" class="nav-link" id="menu_group_link{{$group_menu_sl}}">
                            <i class="nav-icon {{$group_data['g_icon']}}"></i>
                            <p>{{$group_data['g_name']}}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @php
                                $group_cat_sl= 0;

                                foreach($right_cat_arr[$group_data['g_id']] as $cat_data){

                                    $group_cat_sl++;
                            @endphp
                                <li class="nav-item" id="menu_cat{{$group_menu_sl}}{{$group_cat_sl}}" onclick="group_menu_cat_open('{{$group_menu_sl}}','{{$group_cat_sl}}');">
                                    <a href="" class="nav-link" id="menu_cat_link{{$group_menu_sl}}{{$group_cat_sl}}">
                                        <i class="nav-icon {{$cat_data['c_icon']}}"></i>
                                        <p>{{$cat_data['c_name']}}<i class="fas fa-angle-left right"></i></p>
                                     </a>
                                    <ul class="nav nav-treeview">
                                        @php
                                            foreach($right_arr[$group_data['g_id']][$cat_data['c_id']] as $right_data){
                                        @endphp
                                            <li class="nav-item">
                                                <a href="#" onclick="get_new_page('{{route($right_data['r_route_name'])}}','{{$right_data['r_title']}}','','');" class="nav-link">
                                                    <i class="nav-icon {{$right_data['r_icon']}}"></i>
                                                    <p>{{$right_data['r_name']}}</p>
                                                </a>
                                            </li>
                                        @php
                                            }
                                        @endphp
                                    </ul>
                                </li>
                            @php
                                }

                                @endphp

                                <input type="hidden" id="total_sub_menu{{$group_menu_sl}}" name="total_sub_menu{{$group_menu_sl}}" value="{{$group_cat_sl}}">

                                @php
                            @endphp
                        </ul>
                    </li>
                @php
                    }
                @endphp
                <li class="nav-item">
                    <a href="{{route('admin.logout')}}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
<!-- /.sidebar -->
</aside>

<style type="text/css">
    .menu-bg-color{
        background-color: #3f6791 !important;
    }
    .sub-menu-bg-color{
        background-color: #dc3545 !important;
    }
</style>

<script type="text/javascript">

    var input_field_name = '';
    
    function get_new_page(route_name,r_title,data,name) {

        var find_value = "DELETE";

        var to_find = r_title.toUpperCase();

        const regex = new RegExp('\\b' + find_value + '\\b', 'gi');
        const matches = to_find.match(regex);

        if (matches && name!=''){

            const result = window.confirm("Do You Want To Delete This ("+name+")?");

            if(!result){

                return false;
            }
        }


        var name_details = '';

        if(name!=''){

            name_details +=" - "+name;
        }

        document.title = r_title +name_details+ ' | '+'{{!empty($system_data['system_name'])?$system_data['system_name']:'Rental Management System'}}';

        http.open("GET",route_name+"/"+data,true);
        http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        http.send();
        http.onreadystatechange = get_new_page_reponse;
    }

    function get_new_page_reponse(){

        if(http.readyState == 4)
        {
            var reponse=trim(http.responseText).split("****");

            if(reponse[0]=='Session Expire' || reponse[0]=='Right Not Found'){

                alert(http.responseText);

                location.replace('<?php echo url('/dashboard/logout');?>');
            }
            else{

                $("#page_content").html(reponse[0]);

                $('meta[name="csrf-token"]').attr('content', reponse[1]);
            }
        }
    }

    function check_duplicate_value(field_name,table_name,value,data_id){

        input_field_name = field_name;
 
        var data="&field_name="+field_name+"&table_name="+table_name+"&value="+value+"&data_id="+data_id;

        http.open("GET","{{route('admin.get.duplicate.value')}}"+"/"+field_name+"/"+table_name+"/"+value+"/"+data_id,true);
        http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        http.send(data);
        http.onreadystatechange = check_duplicate_value_reponse;
    }

    function check_duplicate_value_reponse(){

        if(http.readyState == 4)
        {

            var reponse=trim(http.responseText).split("****");

            if(reponse[0]=='Session Expire' || reponse[0]=='Right Not Found'){

                location.replace('<?php echo url('/dashboard/logout');?>');
            }
            else{

                if(reponse[0]*1>0){

                    var field_name_arr = input_field_name.split("_");

                    $("#"+input_field_name).val('');

                    alert('Duplicate '+field_name_arr[0]+' '+field_name_arr[1]+' found');

                    return false;
                }
                else{

                    return true;
                }
            }
        }
    }

    function check_duplicate_value_with_two_filed(field_name,field_name2,table_name,value,data_id){

        var value2 = 0;

        var field_name2_arr = field_name2.split(",");

        var field_name2_value = '';

        for(var i=0; i<field_name2_arr.length; i++){

            if(field_name2_value!=''){

                field_name2_value +=",";
            }

            var data_value = $("#"+field_name2_arr[i]).val();

            if(data_value==''){

                field_name2_value += '0';
            }
            else{

                field_name2_value += data_value;
            }
        }

        if(field_name2_value!=''){

            value2 = field_name2_value;
        }

        input_field_name = field_name;
 
        var data="&field_name="+field_name+"&table_name="+table_name+"&value="+value+"&data_id="+data_id+"&value2="+value2+"&field_name2="+field_name2;
 
        http.open("GET","{{route('admin.get.duplicate.value.two')}}"+"/"+field_name+"/"+field_name2+"/"+table_name+"/"+value+"/"+value2+"/"+data_id,true);
        http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        http.send(data);
        http.onreadystatechange = check_duplicate_value_with_two_filed_reponse;
    }

    function check_duplicate_value_with_two_filed_reponse(){

        if(http.readyState == 4)
        {

            var reponse=trim(http.responseText).split("****");

            if(reponse[0]=='Session Expire' || reponse[0]=='Right Not Found'){

                location.replace('<?php echo url('/dashboard/logout');?>');
            }
            else{

                if(reponse[0]*1>0){

                    var field_name_arr = input_field_name.split("_");

                    $("#"+input_field_name).val('');

                    alert('Duplicate '+field_name_arr[0]+' '+field_name_arr[1]+' found');

                    return false;
                }
                else{

                    return true;
                }
            }
        }
    }

    function check_mobile_number(value){

        const bdMobileNumberRegex = /^(01[3-9]\d{8})$/;

        const isValid = bdMobileNumberRegex.test(value);

        if (isValid) 
        {

            return true;
        } 
        else{

            return false;
        }
    }

    function check_date_of_birth(date_of_birth,year){

        const dobDate = new Date(date_of_birth);

        const currentDate = new Date();

        const age = currentDate.getFullYear() - dobDate.getFullYear();

        if (currentDate.getMonth() < dobDate.getMonth() || (currentDate.getMonth() === dobDate.getMonth() && currentDate.getDate() < dobDate.getDate())){

            age--;
        }

        // Check if the age is at least 18
        if (age >= 18) {

            return true;
            
        }
        else{

            return false;
        }
    }

    function load_drop_down(table_name, field_name, id, container, title, select_status,status, selected_value, disabale_status,on_change) {

        var value = 0;

        if(selected_value!=''){

            value = selected_value;
        }

        var http = createObject();

        var data="&table_name="+table_name+"&field_name="+field_name+"&selected_value="+selected_value;

        if( http ) {
            
            http.onreadystatechange = function() {

                if( http.readyState == 4 ) {

                    if( http.status == 200 ){

                        var reponse=trim(http.responseText).split("****");

                        if(reponse[0]=='Session Expire' || reponse[0]=='Right Not Found'){

                            location.replace('<?php echo url('/dashboard/logout');?>');
                        }
                        else{

                            var data = '';

                            if(select_status==1){

                                data ='<select '+on_change+' class="select2 form-control" multiple="" data-placeholder="'+title+'" style="width: 100%;" name="'+id+'" id="'+id+'"><option value="">'+title+'</option>';
                            }
                            else{

                                data ='<select '+on_change+' class="form-control select" style="width: 100%;" name="'+id+'" id="'+id+'"><option value="">'+title+'</option>';
                            }

                            var json_data = JSON.parse(reponse[0]);

                            var data_length = Object.keys(json_data).length;

                            var field_name_arr = field_name.split(',');

                            for(var i=0; i<data_length; i++){

                                data +='<option value="'+json_data[i][field_name_arr[0]]+'">'+json_data[i][field_name_arr[1]]+'</option>';
                            }

                            data +='</select>';

                            document.getElementById(container).innerHTML = data;

                            if(select_status==1){

                                $('.select2').select2();

                                if(selected_value!=''){

                                    var data_arr = selected_value.split(",");

                                    $("#"+id).val(data_arr).trigger("change");
                                }
                            }
                            else{

                                if(selected_value!=''){

                                    $("#"+id).val(selected_value);
                                }
                            }

                            if(disabale_status==1){

                                $("#"+id).attr('disabled','disabled ');
                            }

                            $("#"+id).click();
                        }
                    }
                }
            }

            http.open("GET","{{route('admin.get.parameter.data')}}"+"/"+table_name+"/"+field_name+"/"+value,true);
            http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            http.send(data);
        }
    }

    function load_drop_down_by_id(table_name, field_name, id, container, title, select_status,status, selected_value, disabale_status,data_value,data_name,on_change) {

        var value = 0;

        if(selected_value!=''){

            value = selected_value;
        }

        if(data_value==''){

            data_value = 0;
        }

        var http = createObject();

        var data="&table_name="+table_name+"&field_name="+field_name+"&selected_value="+selected_value+"&data_value="+data_value+"&data_name="+data_name;

        if( http ) {
            
            http.onreadystatechange = function() {

                if( http.readyState == 4 ) {

                    if( http.status == 200 ){

                        var reponse=trim(http.responseText).split("****");

                        if(reponse[0]=='Session Expire' || reponse[0]=='Right Not Found'){

                            location.replace('<?php echo url('/dashboard/logout');?>');
                        }
                        else{

                            var data = '';

                            if(select_status==1){

                                data ='<select '+on_change+' class="select2 form-control" multiple="" data-placeholder="'+title+'" style="width: 100%;" name="'+id+'" id="'+id+'"><option value="">'+title+'</option>';
                            }
                            else{

                                data ='<select '+on_change+' class="form-control select" style="width: 100%;" name="'+id+'" id="'+id+'"><option value="">'+title+'</option>';
                            }

                            var json_data = JSON.parse(reponse[0]);

                            var data_length = Object.keys(json_data).length;

                            var field_name_arr = field_name.split(',');

                            for(var i=0; i<data_length; i++){

                                data +='<option value="'+json_data[i][field_name_arr[0]]+'">'+json_data[i][field_name_arr[1]]+'</option>';
                            }

                            data +='</select>';

                            document.getElementById(container).innerHTML = data;

                            if(select_status==1){

                                $('.select2').select2();

                                if(selected_value!=''){

                                    var data_arr = selected_value.split(",");

                                    $("#"+id).val(data_arr).trigger("change");
                                }
                            }
                            else{

                                if(selected_value!=''){

                                    $("#"+id).val(selected_value);
                                }
                            }

                            if(disabale_status==1){

                                $("#"+id).attr('disabled','disabled ');
                            }

                            $("#"+id).click();
                        }
                    }
                }
            }

            http.open("GET","{{route('admin.get.parameter.data.by.id')}}"+"/"+table_name+"/"+field_name+"/"+value+"/"+data_value+"/"+data_name,true);
            http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            http.send(data);
        }
    }

    function get_data_by_id(div_id, data_id, field_name, table_name) {

        var http = createObject();

        var data="&data_id="+data_id+"&field_name="+field_name+"&table_name="+table_name;

        if( http ) {
            
            http.onreadystatechange = function() {

                if( http.readyState == 4 ) {

                    if( http.status == 200 ){

                        var reponse=trim(http.responseText).split("****");

                        if(reponse[0]=='Session Expire' || reponse[0]=='Right Not Found'){

                            location.replace('<?php echo url('/dashboard/logout');?>');
                        }
                        else{

                            if(reponse[0]!=''){

                                var json_data = JSON.parse(reponse[0]);

                                var value = $("#"+div_id).text();

                                var value_data = ''

                                json_data.forEach(function(item) {

                                    if(value_data!=''){

                                        value_data +=", ";
                                    }

                                    value_data += item[field_name];
                                });

                                $("#"+div_id).text(value+value_data);
                            }
                        }
                    }
                }
            }

            http.open("GET","{{route('admin.get.data.by.id')}}"+"/"+table_name+"/"+data_id+"/"+field_name,true);
            http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            http.send(data);
        }
    }

    function readUrl(input,view_id){
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e){
                $('#'+view_id).attr('src', e.target.result).width(80).height(80);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function group_menu_open(id){

        var total_group = '{{$group_menu_sl}}';

        for(var i=1; i<=total_group; i++){

            if (i!=id){

                $("#menu_group"+i).removeClass("nav-item menu-is-opening menu-open").addClass("nav-item");

                $("#menu_group_link"+i).removeClass("menu-bg-color nav-link").addClass("nav-link");

                var menu_list = $("#menu_group"+i+" ul");

                menu_list.css("display", "none");

                var total_sub_menu = $("#total_sub_menu"+i).val();

                for(var j=1; j<=total_sub_menu; j++){

                    $("#menu_cat_link"+i+j).removeClass("sub-menu-bg-color nav-link").addClass("nav-link");
                }
            }
            else{

                $("#menu_group_link"+i).removeClass("nav-link").addClass("menu-bg-color nav-link");
            }
        }
    }

    function group_menu_cat_open(group_id,cat_id){

        var total_sub_menu = $("#total_sub_menu"+group_id).val();

        for(var i=1; i<=total_sub_menu; i++){

            if (i!=cat_id){

                $("#menu_cat"+group_id+i).removeClass("nav-item menu-is-opening menu-open").addClass("nav-item");

                $("#menu_cat_link"+group_id+i).removeClass("sub-menu-bg-color nav-link").addClass("nav-link");

                var menu_list = $("#menu_cat"+group_id+i+" ul");

                menu_list.css("display", "none");
            }
            else{

                $("#menu_cat_link"+group_id+i).removeClass("nav-link").addClass("sub-menu-bg-color nav-link");
            }
        }
    }

    $(document).ready(function() {
        alert("ddddddd");
  $(".text_boxes_numeric").click(function() {
    alert("ddddddd");
    var contentPanelId = jQuery(this).attr("id");
    if(document.getElementById(contentPanelId).style.backgroundColor!="")
        document.getElementById(contentPanelId).style.backgroundColor="";
         
});
});

    
</script>



                