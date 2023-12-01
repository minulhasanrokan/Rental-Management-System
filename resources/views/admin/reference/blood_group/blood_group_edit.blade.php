@if($header_status==1)
    <div class="container-fluid" style="padding-top: 5px !important;">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                    <div class="card-header">
                        <h3 class="card-title">View All Blood Group Information</h3>
                    </div>
                    <div class="card-header" style="background-color: white;">
                        {!!$menu_data!!}
                    </div>
                    <div class="card" style="margin: 0px !important;">
                        <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                            <table id="blood_group_data_table" class="table table-bordered table-striped" style="text-align: center !important; vertical-align: middle !important;" rules="all">
                                <thead>
                                    <tr>
                                        <th width="50">Sl</th>
                                        <th width="150">Blood Group Name</th>
                                        <th width="150">Blood Group Code</th>
                                        <th>Blood Group Title</th>
                                        <th width="50">Status</th>
                                        <th width="150">Action</th>
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

            $('#blood_group_data_table').DataTable({
                "lengthMenu": [5,10, 20, 50, 100,200],
                "pageLength": 5,
                "serverSide": true,
                "responsive": true,
                "colReorder": true,
                "processing": true,
                "ajax":{
                    "url": "{{route('reference_data.blood_group.edit')}}",
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
                        "data": "blood_group_name",
                        "render": function(data, type, full, meta) {

                            return '<div style="text-align: left !important;">'+data+'</div>';
                        }
                    },
                    { data: "blood_group_code" },
                    {
                        "data": "blood_group_title",
                        "render": function(data, type, full, meta) {

                            return '<div style="text-align: left !important;">'+data+'</div>';
                        }
                    },
                    {
                        "data": "status",
                        "render": function(data, type, full, meta) {

                            if (data === 1) {
                                return '<div style="color:#28a745;">Active</div>';
                            }
                            else{
                                return '<div style="color:#d81b60;">Inactive</div>';
                            }
                            return data;
                        }
                    },
                    {
                        "data": "action",
                        "render": function(data, type, full, meta) {

                            var menu = full.menu_data;
                            var blood_group_name = full.blood_group_name;

                            var menu_data = '';

                            menu.forEach(function(item) {
                                
                                menu_data +='<button onclick="get_new_page(\''+item.r_route_name+'\',\''+item.r_title+'\',\''+data+'\',\''+blood_group_name+'\');" class="btn btn-primary" style="margin-right:5px;"><i class="fa '+item.r_icon+'"></i></button>'
                            });

                            return menu_data;
                        }
                    },
                ],
                columnDefs: [
                    {
                        targets: [0,5], 
                        searchable: false,
                        orderable: false
                    }
                ]
            });
        });

    </script>****{{csrf_token()}}
@else

    @extends('admin.admin_master')

    @section('content')
        <div class="container-fluid" style="padding-top: 5px !important;">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary" style="padding-bottom:0px !important; margin: 0px !important;">
                        <div class="card-header">
                            <h3 class="card-title">View All Blood Group Information</h3>
                        </div>
                        <div class="card-header" style="background-color: white;">
                            {!!$menu_data!!}
                        </div>
                        <div class="card" style="margin: 0px !important;">
                            <div class="card-body" style="padding-bottom:5px !important; padding-top: 10px !important; margin: 0px !important;">
                                <table id="blood_group_data_table" class="table table-bordered table-striped" style="text-align: center !important; vertical-align: middle !important;" rules="all">
                                    <thead>
                                        <tr>
                                            <th width="50">Sl</th>
                                            <th width="150">Blood Group Name</th>
                                            <th width="150">Blood Group Code</th>
                                            <th>Blood Group Title</th>
                                            <th width="50">Status</th>
                                            <th width="150">Action</th>
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

                $('#blood_group_data_table').DataTable({
                    "lengthMenu": [5,10, 20, 50, 100,200],
                    "pageLength": 5,
                    "serverSide": true,
                    "responsive": true,
                    "colReorder": true,
                    "processing": true,
                    "ajax":{
                        "url": "{{route('reference_data.blood_group.edit')}}",
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
                            "data": "blood_group_name",
                            "render": function(data, type, full, meta) {

                                return '<div style="text-align: left !important;">'+data+'</div>';
                            }
                        },
                        { data: "blood_group_code" },
                        {
                            "data": "blood_group_title",
                            "render": function(data, type, full, meta) {

                                return '<div style="text-align: left !important;">'+data+'</div>';
                            }
                        },
                        {
                            "data": "status",
                            "render": function(data, type, full, meta) {

                                if (data === 1) {
                                    return '<div style="color:#28a745;">Active</div>';
                                }
                                else{
                                    return '<div style="color:#d81b60;">Inactive</div>';
                                }
                                return data;
                            }
                        },
                        {
                            "data": "action",
                            "render": function(data, type, full, meta) {

                                var menu = full.menu_data;
                                var blood_group_name = full.blood_group_name;

                                var menu_data = '';

                                menu.forEach(function(item) {
                                    
                                    menu_data +='<button onclick="get_new_page(\''+item.r_route_name+'\',\''+item.r_title+'\',\''+data+'\',\''+blood_group_name+'\');" class="btn btn-primary" style="margin-right:5px;"><i class="fa '+item.r_icon+'"></i></button>'
                                });

                                return menu_data;
                            }
                        },
                    ],
                    columnDefs: [
                        {
                            targets: [0,5], 
                            searchable: false,
                            orderable: false
                        }
                    ]
                });
            });

        </script>
    @endsection

    @section('css')

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

    @endsection

    @section('js')

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
        <!--common js-->
        <script src="{{asset('backend/dist/js/common.js')}}"></script>

    @endsection

@endif