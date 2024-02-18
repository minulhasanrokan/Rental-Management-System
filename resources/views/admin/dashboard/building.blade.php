@php

    if(isset($dash_board_right_arr['r_route_name']['floor_management']['floor_management.building']) || $admin_status==1){

        $total_data = DB::table('buildings as a')
            ->select(DB::raw('COUNT(a.id) as total_data'))
            ->where('a.delete_status', 0)
            ->first();

        $route_name = '';
        $title = '';
        $action_name = '';

        if($admin_status==1){

            $route_name = route('floor_management.building.view');
            $action_name = 'floor_management.building.view';

            $title  = $dash_board_right_arr['r_title']['floor_management']['floor_management.building'][$action_name];
        }
        else{

            foreach($dash_board_right_arr['r_route_name']['floor_management']['floor_management.building'] as $route_data){

                if($route_data!='floor_management.building.add'){

                    $action_name = $route_data;

                    $route_name = route($route_data);
                    $title  = $dash_board_right_arr['r_title']['floor_management']['floor_management.building'][$route_data];

                    if($route_name!=''){

                        break;
                    }
                }
            }
        }
@endphp
    <div class="col-6 col-sm-3 col-md-2">
        <a href="#" onclick="get_new_page('{{$route_name}}','{{$title}}','','');" class="uppercase">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="{{$right_group_icon_arr['floor_management']['floor_management.building']['c_icon']}}"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Building</span>
                    <span class="info-box-number">{{$total_data->total_data}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </a>
    </div>
@php
    }
@endphp