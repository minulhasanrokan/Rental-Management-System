@php

    if(isset($dash_board_right_arr['r_route_name']['maintenance']['maintenance.manage']) || $admin_status==1){

        $cost = DB::table('maintenance_costs as a')
            ->select(DB::raw('SUM(a.cost) as cost'))
            ->where('a.delete_status', 0)
            ->first();

        $route_name = '';
        $title = '';
        $action_name = '';

        if($admin_status==1){

            $route_name = route('maintenance.manage.view');
            $action_name = 'maintenance.manage.view';

            $title  = $dash_board_right_arr['r_title']['maintenance']['maintenance.manage'][$action_name];
        }
        else{

            foreach($dash_board_right_arr['r_route_name']['maintenance']['maintenance.manage'] as $route_data){

                if($route_data!='maintenance.manage.add'){

                    $action_name = $route_data;

                    $route_name = route($route_data);
                    $title  = $dash_board_right_arr['r_title']['maintenance']['maintenance.manage'][$route_data];

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
                <span class="info-box-icon bg-info elevation-1"><i class="{{$right_group_icon_arr['maintenance']['maintenance.manage']['c_icon']}}"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Maintenance Cost</span>
                    <span class="info-box-number">{{$cost->cost}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </a>
    </div>
@php
    }
@endphp