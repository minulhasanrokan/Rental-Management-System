<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rental | Register New Account</title>
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
                    @if(Session::has('message'))
                        <h5 class="text-center">A Verification Link Has Been Sent To The E-mail Address You Provided During Registration.</h5>
                        <button type="button" class="btn btn-block btn-primary"><a style="text-decoration: none; color:white;" href="{{Session::get('user_id')}}">Resend Verification Email</a></button>
                    @else
                    <p class="login-box-msg">Register A New Account</p>
                    <form action="{{route('admin.registration.store')}}" method="post">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Full name" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                            @error('name')
                                <div class="input-error" style="display: inline-block; width:100%; color: red;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <input type="email" name="email" id="email" class="form-control" placeholder="E-mail Address" required>
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
                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile Number" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-mobile"></span>
                                </div>
                            </div>
                            @error('mobile')
                                <div class="input-error" style="display: inline-block; width:100%; color: red;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
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
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Retype password" required>
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
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                        <div class="col-12">
                            <p class="mb-1 text-center">
                                <a href="forgot-password.html">I Forgot My Password</a>
                            </p>
                        </div>
                        <div class="col-12">
                            <p class="mb-1 text-center">
                                <a href="{{route('admin.login')}}">I Already Have A Account</a>
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