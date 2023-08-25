<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rental | Log in</title>
        <link rel="icon" type="image/x-icon" href="{{asset('uploads/icon/rental_icon.png')}}">
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
    <body class="hold-transition login-page" style="background-image: url({{asset('uploads/login_bg/login_register_bg.jpg')}}); background-repeat: no-repeat; background-position: center; background-size: cover;">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="#" class="h1"><b>Rental</b></a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Sign In To Start Your Session</p>
                    @if(Session::has('message'))
                        <p class="text-center input-error" style="color: red;">{{Session::get('message')}}</p>
                    @endif
                    <form action="{{route('admin.login.store')}}" method="post">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            @error('email')
                                <div class="input-error" style="display: inline-block; width:100%; color: red;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
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
                        <!-- /.col -->
                        <div class="input-group mb-3">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <div class="col-12">
                            <p class="mb-1 text-center">
                                <a href="forgot-password.html">I Forgot My Password</a>
                            </p>
                        </div>
                        <div class="col-12">
                            <p class="mb-1 text-center">
                                <a href="{{route('admin.registration')}}">Register A New Account</a>
                            </p>
                        </div>
                    </form>
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