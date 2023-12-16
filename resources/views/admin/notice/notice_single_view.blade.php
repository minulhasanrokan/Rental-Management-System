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
                                        <td colspan="4" style="text-align:center;">Notice Title: {{$notice_data->notice_title}}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;">Gorup Name:</td>
                                        <td style="text-align:left;" id="group_div_id"></td>
                                        <td style="text-align:left;">User Name:</td>
                                        <td style="text-align:left;" id="user_div_id"></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;">Notice Date:</td>
                                        <td style="text-align:left;">{{$notice_data->notice_date}}</td>
                                        <td style="text-align:left;">Notice Status Bill:</td>
                                        <td style="text-align:left;">{{$notice_data->notice_status==1?'Published':'Un Published'}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align:center;">Notice File</td>
                                        <td colspan="2" style="text-align:center;">
                                            @if($notice_data->notice_file!='')
                                                <a href="{{url('uploads/notice')}}/{{$notice_data->notice_file}}" class="nav-link" target="_blank"><i class="fa-solid fa-download"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="text-align:center;">Notice Details</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="text-align:left;">{!!$notice_data->notice_details!!}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-12">
                                @foreach($user_right_data as $data)
                                    <button style="float:left; margin-left:5px;" onclick="get_new_page('{{route($data->r_route_name)}}','{{$data->r_title}}','{{$encrypt_id}}','{{$notice_data->unit_rent}}');" type="button" class="btn btn-primary"><i class="fa {{$data->r_icon}}"></i>&nbsp;{{$data->r_name}}</button>
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
        get_data_by_id('group_div_id', '{{$notice_data->notice_group}}', 'group_name', 'user_groups');
        get_data_by_id('user_div_id', '{{$notice_data->user_id}}', 'name', 'users');
    </script>
</div>****{{csrf_token()}}