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
                    @if(Session::has('message') && Session::has('create_status') && Session::get('create_status')==1 && Session::get('verify_mail')==0)
                        <h5 class="text-center">A Reset Password Link Has Been Sent To The E-mail Address You Provided During Registration.</h5>
                        <a class="btn btn-primary btn-block" style="text-decoration: none; color:white;" href="{{route('admin.forgot.resend.password',Session::get('user_id'))}}">Resend Reset Password Email</a>
                    @elseif(Session::has('message') && Session::has('create_status') && Session::get('create_status')==1 && Session::get('verify_mail')==1)
                        <h5 class="text-center">{{Session::get('message')}}</h5>
                        <a class="btn btn-primary btn-block" style="text-decoration: none; color:white;" href="{{route('admin.resend.verify.email',Session::get('user_id'))}}">Send Verification E-mail</a>
                    @else
                    <p class="login-box-msg">Reset Password</p>
                    @if(Session::has('message'))
                        <p class="text-center input-error" style="color: red;">{{Session::get('message')}}</p>
                    @endif
                    <form action="{{route('admin.forgot.password')}}" method="post">
                        @csrf   
                        <div class="input-group mb-3">
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter Your E-mail Address" required>
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
                        <!-- /.col -->
                        <div class="input-group mb-3">
                            <button type="submit" class="btn btn-primary btn-block">Send Reset Password Link</button>
                        </div>
                        <div class="col-12">
                            <p class="mb-1 text-center">
                                <a href="{{route('admin.login')}}">I Already Have A Account</a>
                            </p>
                        </div>
                        <div class="col-12">
                            <p class="mb-1 text-center">
                                <a href="{{route('admin.registration')}}">Register A New Account</a>
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
            $(".input-error").delay(3000).fadeOut(800); 
        </script>
    </body>
</html>