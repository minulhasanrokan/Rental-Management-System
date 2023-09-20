<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{!empty($system_data['system_name'])?$system_data['system_name']:'Rental Management System'}} | Log in</title>
        <link rel="icon" type="image/x-icon" href="{{asset('uploads/icon')}}/{{!empty($system_data['system_favicon'])?$system_data['system_favicon']:'rental_icon.png'}}">
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="{{asset('backend/dist/css/google_font_Sans_Pro.css')}}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="{{asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('backend/dist/css/adminlte.min.css')}}">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
    </head>
    <body class="hold-transition login-page" style="background-image: url({{asset('uploads/login_bg')}}/{{!empty($system_data['system_bg_image'])?$system_data['system_bg_image']:'login_register_bg.jpg'}}); background-repeat: no-repeat; background-position: center; background-size: cover;">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <p class="h4"><b>{{!empty($system_data['system_name'])?$system_data['system_name']:'Rental Management System'}}</b></p>
                </div>
                <div class="card-body">
                    @if(isset($notification['message']) && $notification['create_status']==1)
                        <p class="text-center">{{$notification['message']}}</p>
                        <div class="input-group mb-3">
                            <a class="btn btn-primary btn-block" style="text-decoration:none; color:white;" href="{{route('admin.login')}}">Sign In</a>
                        </div>
                    @else
                        <p class="text-center">{{$notification['message']}}</p>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.login-box -->
        <!-- jQuery -->
        <script src="{{asset('backend/plugins/jquery/jquery.min.js')}}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{asset('backend/dist/js/adminlte.min.js')}}"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            @if(isset($notification['message']))
                var type = "{{ $notification['alert_type'] }}"
                switch(type){
                    case 'info':
                    toastr.info(" {{ $notification['message'] }} ");
                    break;

                    case 'success':
                    toastr.success(" {{ $notification['message'] }} ");
                    break;

                    case 'warning':
                    toastr.warning(" {{ $notification['message'] }} ");
                    break;

                    case 'error':
                    toastr.error(" {{ $notification['message'] }} ");
                    break; 
                }
            @endif 
        </script>
    </body>
</html>