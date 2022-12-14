@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<!-- Main Sidebar Container -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" id="page-title"></h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- /.content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header" id="table-header">
                            <h3 class="card-title">
                                <button type="button" class="btn btn-primary open-modal" data-route="{{ route('client.create') }}">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add Client
                                </button>
                            </h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 300px;">
                                    <div class="autocomplete-search">                                        
                                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search name/email/mobile" data-route="{{ route('client.search') }}" data-search-route="{{ route('client.recent-search') }}">
                                    </div>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-default search-data" title="Search" data-route="{{ route('client.search-data') }}">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-default reset-data" title="Reset" data-route="{{ route('client.index') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <div id="table-data">
                                
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>
</div>
@include('modal')
@endsection