@php

    $admin_status = Session::get(config('app.app_session_name').'.super_admin_status');

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
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('backend/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @php
                    foreach($right_group_arr as $group_data){
                @endphp
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon {{$group_data['g_icon']}}"></i>
                            <p>{{$group_data['g_name']}}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @php
                                foreach($right_cat_arr[$group_data['g_id']] as $cat_data){
                            @endphp
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
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

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>Layout Options<i class="fas fa-angle-left right"></i><span class="badge badge-info right">6</span></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/layout/top-nav.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Top Navigation</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Top Navigation + Sidebar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/boxed.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Boxed</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/fixed-sidebar.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fixed Sidebar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/fixed-sidebar-custom.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fixed Sidebar <small>+ Custom Area</small></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/fixed-topnav.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fixed Navbar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/fixed-footer.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fixed Footer</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/collapsed-sidebar.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Collapsed Sidebar</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>Charts<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/charts/chartjs.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>ChartJS</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/charts/flot.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Flot</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/charts/inline.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inline</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/charts/uplot.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>uPlot</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tree"></i>
                        <p>UI Elements<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/UI/general.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>General</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/icons.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Icons</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/buttons.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Buttons</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/sliders.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sliders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/modals.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Modals & Alerts</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/navbar.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Navbar & Tabs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/timeline.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Timeline</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/ribbons.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ribbons</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>Forms<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/forms/general.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>General Elements</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/forms/advanced.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Advanced Elements</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/forms/editors.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Editors</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/forms/validation.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Validation</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-table"></i>
                        <p>Tables<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/tables/simple.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Simple Tables</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/tables/data.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>DataTables</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/tables/jsgrid.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>jsGrid</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">EXAMPLES</li>
                <li class="nav-item">
                    <a href="pages/calendar.html" class="nav-link">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Calendar<span class="badge badge-info right">2</span></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/gallery.html" class="nav-link">
                        <i class="nav-icon far fa-image"></i>
                        <p>Gallery</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/kanban.html" class="nav-link">
                        <i class="nav-icon fas fa-columns"></i>
                        <p>Kanban Board</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>Mailbox<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/mailbox/mailbox.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inbox</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/mailbox/compose.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Compose</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/mailbox/read-mail.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Read</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Pages<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/examples/invoice.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Invoice</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/profile.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/e-commerce.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>E-commerce</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/projects.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Projects</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/project-add.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Project Add</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/project-edit.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Project Edit</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/project-detail.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Project Detail</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/contacts.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Contacts</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/faq.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>FAQ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/contact-us.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Contact us</p>
                             </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-plus-square"></i>
                        <p>Extras<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Login & Register v1<i class="fas fa-angle-left right"></i></p>
                             </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="pages/examples/login.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Login v1</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="pages/examples/register.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Register v1</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="pages/examples/forgot-password.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Forgot Password v1</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="pages/examples/recover-password.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Recover Password v1</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/lockscreen.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Lockscreen</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/legacy-user-menu.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Legacy User Menu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/language-menu.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Language Menu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/404.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Error 404</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/500.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Error 500</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/pace.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pace</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/examples/blank.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Blank Page</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="starter.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Starter Page</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-search"></i>
                        <p>Search<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/search/simple.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Simple Search</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/search/enhanced.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Enhanced</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">MISCELLANEOUS</li>
                <li class="nav-item">
                    <a href="iframe.html" class="nav-link">
                        <i class="nav-icon fas fa-ellipsis-h"></i>
                        <p>Tabbed IFrame Plugin</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="https://adminlte.io/docs/3.1/" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Documentation</p>
                    </a>
                </li>
                <li class="nav-header">MULTI LEVEL EXAMPLE</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Level 1</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-circle"></i>
                        <p>Level 1<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Level 2</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Level 2<i class="right fas fa-angle-left"></i></p>
                            </a>
                           <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Level 2</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                   <a href="#" class="nav-link">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Level 1</p>
                    </a>
                </li>
                <li class="nav-header">LABELS</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-danger"></i>
                        <p class="text">Important</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-warning"></i>
                        <p>Warning</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-info"></i>
                        <p>Informational</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
<!-- /.sidebar -->
</aside>

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

                location.replace('<?php echo url('/login');?>');
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

                location.replace('<?php echo url('/login');?>');
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

    function load_drop_down(table_name, field_name, id, container, title, select_status,status) {

        var http = createObject();

        var data="&table_name="+table_name+"&field_name="+field_name;

        if( http ) {
            
            http.onreadystatechange = function() {

                if( http.readyState == 4 ) {

                    if( http.status == 200 ){

                        var reponse=trim(http.responseText).split("****");

                        if(reponse[0]=='Session Expire' || reponse[0]=='Right Not Found'){

                            location.replace('<?php echo url('/login');?>');
                        }
                        else{

                            var data = '';

                            if(select_status==1){

                                data ='<select class="select2 form-control" multiple="" data-placeholder="'+title+'" style="width: 100%;" name="'+id+'" id="'+id+'"><option value="">'+title+'</option>';
                            }
                            else{

                                data ='<select class="form-control select" style="width: 100%;" name="'+id+'" id="'+id+'"><option value="">'+title+'</option>';
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
                            }
                        }
                    }
                }
            }

            http.open("GET","{{route('admin.get.parameter.data')}}"+"/"+table_name+"/"+field_name+"/"+status,true);
            http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            http.send(data);
        }
    }
</script>



                