@extends('admin.admin_master')

@section('content')
    <div class="container-fluid" style="padding-top: 20px !important;">
        <!-- Info boxes -->
        <div class="row">
            <!-- user -->
                @include('admin.dashboard.user')
            <!-- /.user -->
            <!-- tenant -->
                @include('admin.dashboard.tenant')
            <!-- /.tenant -->
            <!-- owner -->
                @include('admin.dashboard.owner')
            <!-- /.owner -->
            <!-- employee -->
                @include('admin.dashboard.employee')
            <!-- /.employee -->
            <!-- building -->
                @include('admin.dashboard.building')
            <!-- /.building -->
            <!-- level -->
                @include('admin.dashboard.level')
            <!-- /.level -->
            <!-- unit -->
                @include('admin.dashboard.unit')
            <!-- /.unit -->
            <!-- rent -->
                @include('admin.dashboard.rent')
            <!-- /.rent -->
            <!-- rent_bill -->
                @include('admin.dashboard.rent_bill')
            <!-- /.rent_bill -->
            <!-- maintance -->
                @include('admin.dashboard.maintance')
            <!-- /.maintance -->
            <!-- salary -->
                @include('admin.dashboard.salary')
            <!-- /.salary -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">            
            <!-- notice -->
                @include('admin.dashboard.notice')
            <!-- /.notice -->
            <!-- my notice -->
                @include('admin.dashboard.my_notice')
            <!-- /.my notice -->
            <!-- complain -->
                @include('admin.dashboard.complain')
            <!-- /.complain -->
            <!-- my complain -->
                @include('admin.dashboard.my_complain')
            <!-- /.my complain -->
            <!-- my visitor -->
                @include('admin.dashboard.visitor')
            <!-- /.my visitor -->
            <!-- my my_visitor -->
                @include('admin.dashboard.my_visitor')
            <!-- /.my my_visitor -->
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!--/. container-fluid -->
@endsection
