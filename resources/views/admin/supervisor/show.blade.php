@extends('layouts.app')

@section('title','View Supervisor')
@section('heading','View Supervisor')
@section('before_css')
@stop

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.supervisor.index')}}"> Supervisor List </a></li>
    <li class="breadcrumb-item active">View Supervisor {{ $supervisor->username }}</li>
@stop

@section('content')
    <div class="col-md-12">

        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                         src="{{ $supervisor->avatarPath }}"
                         alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ $supervisor->username }}</h3>

                <p class="text-muted text-center">{{ $supervisor->email }}</p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- About Me Box -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">About Me</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Username</strong>

                <p class="text-muted">
                    {{ $supervisor->username }}
                </p>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Phone</strong>

                <p class="text-muted">{{ $supervisor->phone }}</p>

                <hr>

                <strong><i class="fas fa-pencil-alt mr-1"></i> E-mail</strong>

                <p class="text-muted">{{ $supervisor->email }}</p>

                <hr>

                <strong><i class="far fa-file-alt mr-1"></i> Status</strong>

                <p class="text-muted">{{ $supervisor->blocked == 1 ? 'blocked' : 'Unblocked' }}</p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
@stop




