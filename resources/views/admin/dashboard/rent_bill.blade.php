@php

    if(isset($dash_board_right_arr['r_route_name']['rent_management']['rent_management.process']) || $admin_status==1){

        $total_amount = DB::table('rent_bills as a')
            ->select(DB::raw('SUM(a.total_amount) as total_amount'))
            ->where('a.delete_status', 0)
            ->first();

        $route_name = '';
        $title = '';
        $action_name = '';

        if($admin_status==1){

            $route_name = route('rent_management.process.view');
            $action_name = 'rent_management.process.view';

            $title  = $dash_board_right_arr['r_title']['rent_management']['rent_management.process'][$action_name];
        }
        else{

            foreach($dash_board_right_arr['r_route_name']['rent_management']['rent_management.process'] as $route_data){

                if($route_data!='rent_management.process.add'){

                    $action_name = $route_data;

                    $route_name = route($route_data);
                    $title  = $dash_board_right_arr['r_title']['rent_management']['rent_management.process'][$route_data];

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
                <span class="info-box-icon bg-info elevation-1"><i class="{{$right_group_icon_arr['rent_management']['rent_management.process']['c_icon']}}"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Rent Amount</span>
                    <span class="info-box-number">{{$total_amount->total_amount}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </a>
    </div>
@php
    }
@endphp