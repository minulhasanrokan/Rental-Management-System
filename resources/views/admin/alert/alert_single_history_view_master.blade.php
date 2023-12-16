@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                    <div class="card-header">
                        <h3 class="card-title">View Notice Information</h3>
                    </div>
                    <div class="card-header" style="background-color: white;">
                        {!!$menu_data!!} 
                    </div>
                    <div class="card" style="margin: 0px !important;">
                        <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td colspan="4" style="text-align:center;">Notice Title: {{$alert_history_data->alert_title}}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left;">User Name:</td>
                                            <td style="text-align:left;" id="user_div_id"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align:center;">Notice Details</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align:left;">{!!$alert_history_data->alert_details!!}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    @foreach($user_right_data as $data)
                                        <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$encrypt_id}}','{{$alert_history_data->alert_title}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
        <script type="text/javascript">
            get_data_by_id('user_div_id', '{{$alert_history_data->user_id}}', 'name', 'users');
        </script>
    </div>
@endsection