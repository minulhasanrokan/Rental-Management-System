<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{!empty($system_data['title'])?$system_data['title']:'Rental Management System'}} | Dashboard</title>
        <link rel="icon" type="image/x-icon" href="{{asset('uploads/icon')}}/{{!empty($system_data['system_favicon'])?$system_data['system_favicon']:'rental_icon.png'}}">
        <!-- css -->
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="{{asset('backend/dist/css/google_font_Sans_Pro.css')}}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{asset('backend/plugins/select2/css/select2.min.css')}}">
        <link rel="stylesheet" href="{{asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{asset('backend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
        <!-- bootstrap slider -->
      <link rel="stylesheet" href="{{asset('backend/plugins/bootstrap-slider/css/bootstrap-slider.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('backend/dist/css/adminlte.min.css')}}">
        <!-- summernote -->
        <link rel="stylesheet" href="{{asset('backend/plugins/summernote/summernote-bs4.min.css')}}">

        <!-- common -->
        <link rel="stylesheet" href="{{asset('backend/dist/css/common.css')}}">

        <!-- jQuery -->
        <script src="{{asset('backend/plugins/jquery/jquery.min.js')}}"></script>
        <!--common js-->
        <script src="{{asset('backend/dist/js/common.js')}}"></script>
        <!-- /.css -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
    </head>
    <body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
        <div class="wrapper">
            <!-- Preloader -->
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__wobble" src="{{asset('uploads/logo')}}/{{!empty($system_data['system_logo'])?$system_data['system_logo']:'rental_logo.png'}}" alt="{{!empty($system_data['title'])?$system_data['title']:'Rental Management System'}}" height="60" width="60">
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
        <!-- Bootstrap -->
        <script src="{{asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- Select2 -->
        <script src="{{asset('backend/plugins/select2/js/select2.full.min.js')}}"></script>
        <!-- overlayScrollbars -->
        <script src="{{asset('backend/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{asset('backend/dist/js/adminlte.js')}}"></script>
        <!-- Bootstrap slider -->
        <script src="{{asset('backend/plugins/bootstrap-slider/bootstrap-slider.min.js')}}"></script>
        <!-- jQuery Mapael -->
        <script src="{{asset('backend/plugins/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
        <script src="{{asset('backend/plugins/raphael/raphael.min.js')}}"></script>
        <script src="{{asset('backend/plugins/jquery-mapael/jquery.mapael.min.js')}}"></script>
        <script src="{{asset('backend/plugins/jquery-mapael/maps/usa_states.min.js')}}"></script>
        <!-- DataTables  & Plugins -->
        <script src="{{asset('backend/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
        <!-- ChartJS -->
        <script src="{{asset('backend/plugins/chart.js/Chart.min.js')}}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{asset('backend/dist/js/demo.js')}}"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="{{asset('backend/dist/js/pages/dashboard2.js')}}"></script>
        <!-- Summernote -->
        <script src="{{asset('backend/plugins/summernote/summernote-bs4.min.js')}}"></script>
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
