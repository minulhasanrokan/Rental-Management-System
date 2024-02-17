@php

    if(isset($dash_board_right_arr['r_route_name']['notice_manage']['notice_manage.manage']) || $admin_status==1){

        $notice_data = DB::table('notices as a')
                ->leftjoin('user_groups as b', 'b.id', '=', 'a.notice_group')
                ->leftjoin('users as c', 'c.id', '=', 'a.user_id')
                ->select('a.id','a.notice_title')
                ->where('a.delete_status',0)
                ->orderBy('a.id','DESC')
                ->offset(0)
                ->limit(5)
                ->get();

        if(count($notice_data)>0){

            $route_name = '';
            $title = '';
            $icon = '';
            $action_name = '';

            if($admin_status==1){

                $route_name = route('notice_manage.manage.view');
                $action_name = 'notice_manage.manage.view';

                $title  = $dash_board_right_arr['r_title']['notice_manage']['notice_manage.manage'][$action_name];
                $icon   = $dash_board_right_arr['r_icon']['notice_manage']['notice_manage.manage'][$action_name];
            }
            else{

                foreach($dash_board_right_arr['r_route_name']['notice_manage']['notice_manage.manage'] as $route_data){

                    if($route_data!='notice_manage.manage.add'){

                        $route_name = route($route_data);
                        $title  = $dash_board_right_arr['r_title']['notice_manage']['notice_manage.manage'][$route_data];
                        $icon   = $dash_board_right_arr['r_icon']['notice_manage']['notice_manage.manage'][$route_data];

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
                    <h3 class="card-title">{{$dash_board_right_arr['r_title']['notice_manage']['notice_manage.manage'][$action_name]}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @foreach($notice_data as $notice)
                        <li class="item">
                            <div class="product-img">
                                {{substr($notice->notice_title,0,40)}}.....
                            </div>
                            <div class="product-info">
                                <a href="#" onclick="get_new_page('{{$route_name}}','{{$notice->notice_title}}','{{$common->encrypt_data($notice->id)}}','{{$dash_board_right_arr['r_title']['notice_manage']['notice_manage.manage'][$action_name]}}');" class="product-title">
                                    <span class="badge badge-warning float-right"><i class="nav-icon {{$dash_board_right_arr['r_icon']['notice_manage']['notice_manage.manage'][$action_name]}}"></i></span>
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                    <a href="#" onclick="get_new_page('{{$route_name}}','{{$dash_board_right_arr['r_title']['notice_manage']['notice_manage.manage'][$action_name]}}','','');" class="uppercase">{{$dash_board_right_arr['r_title']['notice_manage']['notice_manage.manage'][$action_name]}}</a>
                </div>
                <!-- /.card-footer -->
            </div>
        </div>
@php
        }
    }
@endphp