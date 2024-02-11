@php

    if(isset($dash_board_right_arr['r_route_name']['complain_management']['complain_management.manage']) || $admin_status==1){

        $complain_data = DB::table('complains as a')
                ->leftjoin('users as b', 'b.id', '=', 'a.assign_user')
                ->select('a.id','a.complain_title')
                ->where('a.delete_status',0)
                ->orderBy('a.id','DESC')
                ->offset(0)
                ->limit(5)
                ->get();

        if(count($complain_data)>0){

            $route_name = '';
            $title = '';
            $icon = '';

            if($admin_status==1){

                $route_name = route('complain_management.manage.view');

                $title  = $dash_board_right_arr['r_title']['complain_management']['complain_management.manage']['complain_management.manage.view'];
                $icon   = $dash_board_right_arr['r_icon']['complain_management']['complain_management.manage']['complain_management.manage.view'];
            }
            else{

                foreach($dash_board_right_arr['r_route_name']['complain_management']['complain_management.manage'] as $route_data){

                    if($route_data!='complain_management.manage.add'){

                        $route_name = route($route_data);
                        $title  = $dash_board_right_arr['r_title']['complain_management']['complain_management.manage'][$route_data];
                        $icon   = $dash_board_right_arr['r_icon']['complain_management']['complain_management.manage'][$route_data];

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
                    <h3 class="card-title">{{$dash_board_right_arr['r_title']['complain_management']['complain_management.manage']['complain_management.manage.view']}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @foreach($complain_data as $complain)
                        <li class="item">
                            <div class="product-img">
                                {{substr($complain->complain_title,0,40)}}.....
                            </div>
                            <div class="product-info">
                                <a href="#" onclick="get_new_page('{{$route_name}}','{{$complain->complain_title}}','{{$common->encrypt_data($complain->id)}}','{{$dash_board_right_arr['r_title']['complain_management']['complain_management.manage']['complain_management.manage.view']}}');" class="product-title">
                                    <span class="badge badge-warning float-right"><i class="nav-icon {{$dash_board_right_arr['r_icon']['complain_management']['complain_management.manage']['complain_management.manage.view']}}"></i></span>
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                    <a href="#" onclick="get_new_page('{{$route_name}}','{{$dash_board_right_arr['r_title']['complain_management']['complain_management.manage']['complain_management.manage.view']}}','','');" class="uppercase">{{$dash_board_right_arr['r_title']['complain_management']['complain_management.manage']['complain_management.manage.view']}}</a>
                </div>
                <!-- /.card-footer -->
            </div>
        </div>
@php
        }
    }
@endphp