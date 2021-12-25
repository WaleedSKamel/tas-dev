@extends('auth.master')

@section('title','Reset Password Supervisor')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{!! route('supervisor.login') !!}"><b>Admin</b>LTE</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Reset Password for supervisor</p>

                <form action="{{ route('supervisor.reset',$data->token) }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $data->token }}">
                    <div class="input-group mb-3">
                        <input type="email" name="email" value="{{ old('email',$data->email) }}" class="form-control @error('email') is-invalid @enderror " placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                        <span id="exampleInputEmail1-error" class="error invalid-feedback">{{ $message }}</span>
                        @enderror

                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                        <span id="exampleInputEmail1-error" class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Password Confirmation">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password_confirmation')
                        <span id="exampleInputEmail1-error" class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                        </div>
                        <!-- /.col -->
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
