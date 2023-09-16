<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{!empty($system_data['system_name'])?$system_data['system_name']:'Rental Management System'}} | Dashboard</title>
        <link rel="icon" type="image/x-icon" href="{{asset('uploads/icon')}}/{{!empty($system_data['system_favicon'])?$system_data['system_favicon']:'rental_icon.png'}}">
        <!-- css -->
            @yield('css')
        <!-- /.css -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
    </head>
    <body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
        <div class="wrapper">
            <!-- Preloader -->
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__wobble" src="{{asset('backend/dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">
            </div>
            <!-- Navbar -->
                @include('admin.includes.header')
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
                @include('admin.includes.sidebar')
            <!-- /.Main Sidebar Container -->
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content" id="page_content">
                    @yield('content')
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <!-- Main Footer -->
                @include('admin.includes.footer')
            <!-- /.Main Footer -->
        </div>
        <!-- ./wrapper -->

        <!-- js -->
            @yield('js')
        <!-- /.js -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            @if(Session::has('message'))
                var type = "{{ Session::get('alert_type','info') }}"
                switch(type){
                    case 'info':
                    toastr.info(" {{ Session::get('message') }} ");
                    break;

                    case 'success':
                    toastr.success(" {{ Session::get('message') }} ");
                    break;

                    case 'warning':
                    toastr.warning(" {{ Session::get('message') }} ");
                    break;

                    case 'error':
                    toastr.error(" {{ Session::get('message') }} ");
                    break; 
                }
            @endif 
        </script>
        <script type="text/javascript">
            
            // hide all input error.............
            //$(".input-error").delay(3000).fadeOut(800); 
        </script>
    </body>
</html>
