@extends('auth.master')

@section('title','Reset Password Supervisor')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{!! route('admin.login') !!}"><b>Admin</b>LTE</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Reset Password for supervisor</p>

                <form action="{{ route('supervisor.reset.password') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror " placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                        <span id="exampleInputEmail1-error" class="error invalid-feedback">{{ $message }}</span>
                        @enderror

                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Send Link Reset Password</button>
                        </div>
                    </div>

                    <p class="mb-1">
                        <a href="{{ route('supervisor.login') }}">Login</a>
                    </p>
                </form>



            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@stop
