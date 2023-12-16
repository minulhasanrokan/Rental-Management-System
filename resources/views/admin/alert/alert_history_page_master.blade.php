@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 5px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                    <div class="card-header">
                        <h3 class="card-title">Alert History Information({{!empty($alert_data)?$alert_data->alert_title:''}})</h3>
                    </div>
                    <div class="card-header" style="background-color: white;">
                        {!!$menu_data!!}
                    </div>
                    <div class="card" style="margin: 0px !important;">
                        <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                            <table id="level_data_table" class="table table-bordered table-striped" style="text-align: center !important; vertical-align: middle !important;" rules="all">
                                <thead>
                                    <tr>
                                        <th width="10">Sl</th>
                                        <th width="80">User</th>
                                        <th width="200">Title</th>
                                        <th width="60">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
    </div>
    <script>
        $(document).ready(function () {

            var token = $('meta[name="csrf-token"]').attr('content');

            $('#level_data_table').DataTable({
                "lengthMenu": [5,10, 20, 50, 100,200],
                "pageLength": 5,
                "serverSide": true,
                "responsive": true,
                "colReorder": true,
                "processing": true,
                "ajax":{
                    "url": "{{route('sms_email_alert.manage.history',!empty($alert_data)?$alert_data->id:'0')}}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: token},
                    "error": function(xhr, status, error) {
                        
                        alert(xhr.responseText);

                        location.replace('<?php echo url('/dashboard/logout');?>');
                    },
                    "dataSrc": function ( json ) {
                            
                        if(json.csrf_token !== undefined){

                            $('meta[name=csrf-token]').attr("content", json.csrf_token);
                            $('input[name=_token]').attr("value", json.csrf_token);
                        }

                        return json.aaData;
                    }
                },
                "order": [
                    [0, 'desc']
                ],
                "columns": [
                    {
                        "data": "id",
                        "render": function(data, type, full, meta) {

                            return  full.sl;
                        }
                    },
                    {
                        "data": "user_name",
                        "render": function(data, type, full, meta) {

                            var user_name = full.user_name;

                            if(user_name==null){

                                return "All";
                            }
                            else
                            {
                                return user_name;
                            }
                        }
                    },
                    { data: "alert_title" },
                    {
                        "data": "action",
                        "render": function(data, type, full, meta) {

                            var menu = full.menu_data;
                            var alert_title = full.alert_title;

                            var menu_data = '';

                            menu.forEach(function(item) {
                                
                                menu_data +='<button onclick="get_new_page(\''+item.r_route_name+'\',\''+item.r_title+'\',\''+data+'\',\''+alert_title+'\');" class="btn btn-primary" style="margin-right:5px;"><i class="fa '+item.r_icon+'"></i></button>'
                            });

                            return menu_data;
                        }
                    },
                ],
                columnDefs: [
                    {
                        targets: [0,3],
                        searchable: false,
                        orderable: false
                    }
                ]
            });
        });
    </script>
@endsection