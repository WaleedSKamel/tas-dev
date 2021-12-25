@extends('layouts.app')

@section('title',$edit ?  'Edit Category' : 'Create Category')
@section('heading',$edit ?  'Edit Category' : 'Create Category')


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('supervisor.category.index')}}"> Category List </a></li>
    <li class="breadcrumb-item active">{{ $edit ? 'Edit Category' : 'Create Category' }}</li>
@stop

@section('content')
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-{{ $edit ? 'success' : 'primary' }}">
            <div class="card-header">
                <h3 class="card-title"> {{ $edit ?  'Edit Category' : 'Create Category' }}</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            @if ($edit)
                {!! Form::open(['route' => ['supervisor.category.update',$category->id],'files'=>true,'method'=>'put']) !!}
                {!! Form::hidden('id',$category->id) !!}
            @else
                {!! Form::open(['route' => 'supervisor.category.store','files'=>true,'method'=>'post']) !!}
            @endif
                <div class="card-body">
                    <div class="row">

                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Category Name</label>
                                <input type="text" name="name" value="{{ old('name',$edit ? $category->name : '') }}" class="form-control @error('name') is-invalid @enderror"
                                       id="name" placeholder="Enter Category Name">
                                @error('name')
                                <span id="exampleInputEmail1-error" class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Icon</label>
                                <img src="{{ $edit ? $category->iconPath : asset("assets/images/no-user.jpg")}}" class="icon-preview" height="100px" width="100px">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="icon" class="custom-file-input  icon @error('icon') is-invalid @enderror" id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">Choose Icon</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    @error('icon')
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
    @include('layouts.partials.read-photo',['inputName' => 'icon'])
@endpush


