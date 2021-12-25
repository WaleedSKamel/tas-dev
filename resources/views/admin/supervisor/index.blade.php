@extends('layouts.app')

@section('title','Supervisor List')
@section('heading','Supervisors')
@section('before_css')
    @include('layouts.partials.datatables.css')
    <style type="text/css">

        .checkbox, #chk_all{
            width: 15px;
            height: 15px;
        }
    </style>
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">Supervisor List</li>
@stop

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Supervisor List
                    <a href="{{ route("admin.supervisor.create") }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Supervisor</a></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>
                            @if($data->count() > 0)
                                <input type="checkbox" id="chk_all">
                            @endif
                        </th>
                        <th>#</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Blocked</th>
                        <th>Created On</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key => $row)
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="checkbox" id="chk{{ $row->id }}" onclick='checkcheckbox();'>
                            </td>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $row->username }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->phone }}</td>
                            <td>{{ $row->blocked == 1 ? 'Block' : 'Unblock' }}</td>
                            <td>{{$row->created_at->diffForHumans() }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                        <span class="fa fa-gear"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu custom" role="menu">
                                        <a class="dropdown-item changepass"  data-id="{{$row->id}}" data-toggle="modal" data-target="#changepass" title="Change Password"><i class="fa fa-key" ></i> Change Password</a>
                                            <a class="dropdown-item" href="{{ route('admin.supervisor.show',$row->id)}}"> <span aria-hidden="true" class="fa fa-eye" ></span> View</a>
                                        <a class="dropdown-item" href="{{ route('admin.supervisor.edit',$row->id)}}"> <span aria-hidden="true" class="fa fa-edit" ></span> Edit</a>
                                            <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" ></span> Delete</a>
                                        @if($row->blocked == 1)
                                            <a class="dropdown-item" href="{{ route('admin.supervisor.status',$row->id) }}" title="Block Supervisor"><span class="fa fa-times"></span>  Block Supervisor</a>
                                        @else
                                            <a class="dropdown-item" href="{{ route('admin.supervisor.status',$row->id) }}" title="Unblock Supervisor"><span class="fa fa-check" > </span> Unblock Supervisor
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                {!! Form::open(['url' => route('admin.supervisor.destroy',$row->id),'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]) !!}
                                {!! Form::hidden("id",$row->id) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>
                            @if($data->count() > 0)
                                    <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" disabled>Delete</button>
                            @endif
                        </th>
                        <th>#</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Blocked</th>
                        <th>Created On</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>


    <!-- Modal -->
    <div id="bulkModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url'=> route('admin.supervisor.multiple-delete'),'method'=>'POST','id'=>'form_delete']) !!}
                    <div id="bulk_hidden"></div>
                    <p>Are you sure you want to Delete selected records..?</p>
                </div>
                <div class="modal-footer">
                    <button id="bulk_action" class="btn btn-danger" type="submit" data-submit="">Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to Delete..?</p>
                </div>
                <div class="modal-footer">
                    <button id="del_btn" class="btn btn-danger" type="button" data-submit="">Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- Modal -->
    <div id="changepass" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Password</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="change" action="{{ route('admin.supervisor.change-password')}}" method="POST">
                        @csrf
                        {!! Form::hidden('id',"",['id'=>'supervisor_id'])!!}
                        <div class="form-group">
                            {!! Form::label('password','Password',['class'=>"form-label"]) !!}
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span></div>
                                {!! Form::password('password',['class'=>"form-control",'id'=>'password']) !!}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="password" class="btn btn-info" type="submit">Change Password</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
@stop

@section('before_js')

    @include('layouts.partials.datatables.js')
@stop

@push('js')
    <script>

        $("#del_btn").on("click", function () {
            var id = $(this).data("submit");
            $("#form_" + id).submit();
        });

        $('#myModal').on('show.bs.modal', function (e) {
            var id = e.relatedTarget.dataset.id;
            $("#del_btn").attr("data-submit", id);
        });

        $('#changepass').on('show.bs.modal', function (e) {
            var id = e.relatedTarget.dataset.id;
            $("#supervisor_id").val(id);
        });

        $('input[type="checkbox"]').on('click', function () {
            $('#bulk_delete').removeAttr('disabled');
        })

        $('#bulk_delete').on('click', function () {
            // console.log($( "input[name='ids[]']:checked" ).length);
            if ($("input[name='ids[]']:checked").length == 0) {
                $('#bulk_delete').prop('type', 'button');
                alertify.error("You must have to select any of one checkbox to delete record.").delay(10000);
                $('#bulk_delete').attr('disabled', true);
            }
            if ($("input[name='ids[]']:checked").length > 0) {
                // var favorite = [];
                $.each($("input[name='ids[]']:checked"), function () {
                    // favorite.push($(this).val());
                    $("#bulk_hidden").append('<input type=hidden name=ids[] value=' + $(this).val() + '>');
                });
                // console.log(favorite);
            }
        });


        $('#chk_all').on('click', function () {
            if (this.checked) {
                $('.checkbox').each(function () {
                    $('.checkbox').prop("checked", true);
                });
            } else {
                $('.checkbox').each(function () {
                    $('.checkbox').prop("checked", false);
                });
            }
        });

        // Checkbox checked
        function checkcheckbox() {
            // Total checkboxes
            var length = $('.checkbox').length;
            // Total checked checkboxes
            var totalchecked = 0;
            $('.checkbox').each(function () {
                if ($(this).is(':checked')) {
                    totalchecked += 1;
                }
            });
            // console.log(length+" "+totalchecked);
            // Checked unchecked checkbox
            if (totalchecked == length) {
                $("#chk_all").prop('checked', true);
            } else {
                $('#chk_all').prop('checked', false);
            }
        }
    </script>
@endpush


