@php

    if(isset($dash_board_right_arr['r_route_name']['visitor_management']['visitor_management.visitor']) || $admin_status==1){

        $visitors_data = DB::table('visitors as a')
            ->join('buildings as b', 'b.id', '=', 'a.building_id')
            ->join('levels as c', 'c.id', '=', 'a.level_id')
            ->join('units as d', 'd.id', '=', 'a.unit_id')
            ->join('users as e', 'e.id', '=', 'a.tenant_id')
            ->select('a.id', 'a.entry_date', 'a.entry_time', 'a.visitor_name', 'a.visitor_mobile', 'a.visitor_address', 'a.tenant_name', 'a.out_date', 'a.out_time', 'a.visitor_reason', 'b.building_name', 'c.level_name', 'd.unit_name', 'e.name')
            ->where('a.delete_status',0)
            ->where('b.delete_status',0)
            ->where('c.delete_status',0)
            ->where('d.delete_status',0)
            ->where('e.delete_status',0)
            ->orderBy('a.id','DESC')
            ->offset(0)
            ->limit(5)
            ->get();

        if(count($visitors_data)>0){

            $route_name = '';
            $title = '';
            $icon = '';
            $action_name = '';

            if($admin_status==1){

                $route_name = route('visitor_management.visitor.view');
                $action_name = 'visitor_management.visitor.view';

                $title  = $dash_board_right_arr['r_title']['visitor_management']['visitor_management.visitor'][$action_name];
                $icon   = $dash_board_right_arr['r_icon']['visitor_management']['visitor_management.visitor'][$action_name];
            }
            else{

                foreach($dash_board_right_arr['r_route_name']['visitor_management']['visitor_management.visitor'] as $route_data){

                    if($route_data!='visitor_management.visitor.add'){

                        $route_name = route($route_data);
                        $title  = $dash_board_right_arr['r_title']['visitor_management']['visitor_management.visitor'][$route_data];
                        $icon   = $dash_board_right_arr['r_icon']['visitor_management']['visitor_management.visitor'][$route_data];

                        if($route_name!=''){

                            break;
                        }
                    }
                }
            }
@endphp
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{$dash_board_right_arr['r_title']['visitor_management']['visitor_management.visitor'][$action_name]}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @foreach($visitors_data as $visitors)
                        <li class="item">
                            <div class="product-img">
                                {{substr($visitors->visitor_name,0,40)}}.....
                            </div>
                            <div class="product-info">
                                <a href="#" onclick="get_new_page('{{$route_name}}','{{$visitors->visitor_name}}','{{$common->encrypt_data($visitors->id)}}','{{$dash_board_right_arr['r_title']['visitor_management']['visitor_management.visitor'][$action_name]}}');" class="product-title">
                                    <span class="badge badge-warning float-right"><i class="nav-icon {{$dash_board_right_arr['r_icon']['visitor_management']['visitor_management.visitor'][$action_name]}}"></i></span>
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                    <a href="#" onclick="get_new_page('{{$route_name}}','{{$dash_board_right_arr['r_title']['visitor_management']['visitor_management.visitor'][$action_name]}}','','');" class="uppercase">{{$dash_board_right_arr['r_title']['visitor_management']['visitor_management.visitor'][$action_name]}}</a>
                </div>
                <!-- /.card-footer -->
            </div>
        </div>
@php
        }
    }
@endphp