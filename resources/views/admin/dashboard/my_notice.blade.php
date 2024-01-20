@php

    if(isset($dash_board_right_arr['r_route_name']['notice_manage.my_notice.view']) || $admin_status==1)
    {
        $user_id = $user_session_data[config('app.app_session_name')]['id'];
        $user_group_id = $user_session_data[config('app.app_session_name')]['group'];

        $route_name = route('notice_manage.my_notice.view');

        $notice_data = DB::table('notices as a')
            ->leftjoin('user_groups as b', 'b.id', '=', 'a.notice_group')
            ->leftjoin('users as c', 'c.id', '=', 'a.user_id')
            ->select('a.id','a.notice_title')
            ->where('a.delete_status',0)
            ->where('a.notice_status', 1)
            ->where(function ($query) use ($user_id) {
                $query->whereIn('a.user_id', [$user_id, 0])
                    ->orWhere('a.user_id', '')
                    ->orWhereNull('a.user_id');
            })
            ->where(function ($query) use ($user_group_id) {

                $query->whereIn('a.notice_group', [$user_group_id, 0])
                    ->orWhere('a.notice_group', '')
                    ->orWhereNull('a.notice_group');
            })
            ->orderBy('a.id','DESC')
            ->offset(0)
            ->limit(5)
            ->get();

        if(count($notice_data)>0){
@endphp
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{$dash_board_right_arr['r_title']['notice_manage.my_notice.view']}}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
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
                            <a href="#" onclick="get_new_page('{{$route_name}}','{{$notice->notice_title}}','{{$common->encrypt_data($notice->id)}}','{{$dash_board_right_arr['r_title']['notice_manage.my_notice.view']}}');" class="product-title">
                                <span class="badge badge-warning float-right"><i class="nav-icon {{$dash_board_right_arr['r_icon']['notice_manage.my_notice.view']}}"></i></span>
                            </a>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-center">
                <a href="#" onclick="get_new_page('{{$route_name}}','{{$dash_board_right_arr['r_title']['notice_manage.my_notice.view']}}','','');" class="uppercase">{{$dash_board_right_arr['r_title']['notice_manage.my_notice.view']}}</a>
            </div>
            <!-- /.card-footer -->
        </div>
@php
        }
    }
@endphp