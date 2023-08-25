<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{asset('uploads/icon/rental_icon.png')}}">
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
                <!-- Content Header (Page header) -->
                    @include('admin.includes.content_header')
                <!-- /.content-header -->
                <!-- Main content -->
                @yield('content')
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
                var type = "{{ Session::get('alert-type','info') }}"
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
            $(".input-error").delay(3000).fadeOut(800); 
        </script>
    </body>
</html>
