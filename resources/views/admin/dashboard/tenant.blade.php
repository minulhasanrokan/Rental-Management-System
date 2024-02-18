@php

    if(isset($dash_board_right_arr['r_route_name']['user_management']['user_management.tenant']) || $admin_status==1){

        $total_data = DB::table('users as a')
            ->select(DB::raw('COUNT(a.id) as total_data'))
            ->where('a.delete_status', 0)
            ->where('a.user_type', $user_config_data['tenant_user_type'])
            ->first();

        $route_name = '';
        $title = '';
        $action_name = '';

        if($admin_status==1){

            $route_name = route('user_management.tenant.view');
            $action_name = 'user_management.tenant.view';

            $title  = $dash_board_right_arr['r_title']['user_management']['user_management.tenant'][$action_name];
        }
        else{

            foreach($dash_board_right_arr['r_route_name']['user_management']['user_management.tenant'] as $route_data){

                if($route_data!='user_management.tenant.add'){

                    $action_name = $route_data;

                    $route_name = route($route_data);
                    $title  = $dash_board_right_arr['r_title']['user_management']['user_management.tenant'][$route_data];

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
                <span class="info-box-icon bg-info elevation-1"><i class="{{$right_group_icon_arr['user_management']['user_management.tenant']['c_icon']}}"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Tenant</span>
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