<script type="text/javascript"></script>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{!empty($system_data['system_name'])?$system_data['system_name']:'Rental Management System'}} | Register New Account</title>
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
                    @if(isset($notification['message']))
                        <h5 class="text-center">{{$notification['message']}}</h5>
                    @endif
                    @if(isset($notification['message']) && isset($notification['create_status']) && $notification['create_status']==2)
                        <button type="button" class="btn btn-block btn-primary"><a style="text-decoration: none; color:white;" href="{{route('admin.login')}}">Login Your Account</a></button>
                    @endif
                    @if(isset($notification['message']) && isset($notification['create_status']) && $notification['create_status']==0)
                    <p class="login-box-msg">Reset Your Password</p>
                    <form action="{{route('admin.change.password')}}" method="post">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="hidden" name="hidden_token" id="hidden_token" class="form-control" value="{{$notification['user_id']}}" required>
                            <input type="password" name="password" id="password" class="form-control" placeholder="New Password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            @error('password')
                                <div class="input-error" style="display: inline-block; width:100%; color: red;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Retype New password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            @error('confirm_password')
                                <div class="input-error" style="display: inline-block; width:100%; color: red;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <!-- /.col -->
                        <div class="input-group mb-3">
                            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                        </div>
                        <div class="col-12">
                            <p class="mb-1 text-center">
                                <a href="{{route('admin.logout')}}">Logout</a>
                            </p>
                        </div>
                    </form>
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
        <script type="text/javascript">
            // hide all input error.............
            //$(".input-error").delay(3000).fadeOut(800); 
        </script>
    </body>
</html>