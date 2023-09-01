<div class="container-fluid" style="padding-top: 20px !important;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">View All User Group Information</h3>
                </div>
                <div class="card-header" style="background-color: white;">
                    {!!$menu_data!!}
                </div>
                <div class="card">
                    <div class="card-body">
                        <table id="group_data_table" class="table table-bordered table-striped" style="text-align: center !important; vertical-align: middle !important;" rules="all">
                            <thead>
                                <tr>
                                    <th width="50">Sl</th>
                                    <th width="50">Image</th>
                                    <th width="50">Icon</th>
                                    <th width="200">Name</th>
                                    <th>Title</th>
                                    <th width="100">Code</th>
                                    <th width="50">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr>
                                    <th width="50">Sl</th>
                                    <th width="50">Image</th>
                                    <th width="50">Icon</th>
                                    <th width="200">Name</th>
                                    <th>Title</th>
                                    <th width="100">Code</th>
                                    <th width="50">Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
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

        $('#group_data_table').DataTable({
            "lengthMenu": [2,5,10, 20, 50, 100,200],
            "pageLength": 5,
            "serverSide": true,
            "processing": false,
            "ajax":{
                "url": "{{route('user_management.user_group.view')}}",
                "dataType": "json",
                "type": "POST",
                "data":{ _token: token},
                "error": function(xhr, status, error) {
                    
                    alert(xhr.responseText);

                    location.replace('<?php echo url('/login');?>');
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
                { data: "id" },
                {
                    "data": "group_logo",
                    "render": function(data, type, full, meta, title) {

                        var titleValue = full.group_name;

                        if (type === 'display') {
                            return '<img src="{{url('uploads/user_group')}}/' + data + '" alt="'+titleValue+'" width="35" height="35">';
                        }
                        return data;
                    }
                },
                {
                    "data": "group_icon",
                    "render": function(data, type, full, meta) {

                        var titleValue = full.group_name;

                        if (type === 'display') {
                            return '<img src="{{url('uploads/user_group')}}/' + data + '" alt="'+titleValue+'" width="35" height="35">';
                        }
                        return data;
                    }
                },
                {
                    "data": "group_name",
                    "render": function(data, type, full, meta) {

                        return '<div style="text-align: left !important;">'+data+'</div>';
                    }
                },
                {
                    "data": "group_title",
                    "render": function(data, type, full, meta) {

                        return '<div style="text-align: left !important;">'+data+'</div>';
                    }
                },
                { data: "group_code" },
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
                { data: "action" },
            ],
            columnDefs: [
                {
                    targets: [0,1,2,7], 
                    searchable: false,
                    orderable: false
                }
            ]
        });
    });

</script>****{{csrf_token()}}

