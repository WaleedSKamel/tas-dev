@extends('layouts.app')

@section('title',$edit ?  'Edit Supervisor' : 'Create Supervisor')
@section('heading',$edit ?  'Edit Supervisor' : 'Create Supervisor')


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.supervisor.index')}}"> Supervisor List </a></li>
    <li class="breadcrumb-item active">{{ $edit ? 'Edit Supervisor' : 'Create Supervisor' }}</li>
@stop

@section('content')
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-{{ $edit ? 'success' : 'primary' }}">
            <div class="card-header">
                <h3 class="card-title"> {{ $edit ?  'Edit Supervisor' : 'Create Supervisor' }}</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            @if ($edit)
                {!! Form::open(['route' => ['admin.supervisor.update',$supervisor->id],'files'=>true,'method'=>'put']) !!}
                {!! Form::hidden('id',$supervisor->id) !!}
            @else
                {!! Form::open(['route' => 'admin.supervisor.store','files'=>true,'method'=>'post']) !!}
            @endif
                <div class="card-body">
                    <div class="row">

                        <div class="col-6">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" value="{{ old('username',$edit ? $supervisor->username : '') }}" class="form-control @error('username') is-invalid @enderror" id="username" placeholder="Enter Username">
                                @error('username')
                                <span id="exampleInputEmail1-error" class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone',$edit ? $supervisor->phone : '') }}" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" placeholder="Enter Phone">
                                @error('phone')
                                <span id="exampleInputEmail1-error" class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" value="{{ old('email',$edit ? $supervisor->email : '') }}" class="form-control @error('email') is-invalid @enderror"
                                       id="email" placeholder="Enter Email">
                                @error('email')
                                <span id="exampleInputEmail1-error" class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if (!$edit)
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror"
                                           id="password" placeholder="Enter Phone">
                                    @error('password')
                                    <span id="exampleInputEmail1-error" class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Avatar</label>
                                <img src="{{ $edit ? $supervisor->avatarPath : asset("assets/images/no-user.jpg")}}" class="avatar-preview" height="100px" width="100px">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="avatar" class="custom-file-input  avatar @error('avatar') is-invalid @enderror" id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">Choose Image</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    @error('avatar')
                                    <span id="exampleInputEmail1-error" class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-{{ $edit ? 'success' : 'primary' }}"> {{ $edit ? 'Update' : 'Save' }}</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop

@push('js')
    @include('layouts.partials.read-photo',['inputName' => 'avatar'])
@endpush


