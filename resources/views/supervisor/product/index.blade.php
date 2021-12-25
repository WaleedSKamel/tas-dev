@extends('layouts.app')

@section('title','Product List')
@section('heading','Products')
@section('before_css')
    @include('layouts.partials.datatables.css')
    <style type="text/css">

        .checkbox, #chk_all {
            width: 15px;
            height: 15px;
        }
    </style>
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">Product List</li>
@stop

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Product List
                    <a href="{{ route("supervisor.product.create") }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Product</a></h3>
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
                        <th>Category Name</th>
                        <th>Product Name</th>
                        <th>Image</th>
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
                            <td>{{ $row->category->name }}</td>
                            <td>{{ $row->name }}</td>
                            <td><img src="{{$row->imagePath}}" height="70px" width="70px"></td>
                            <td>{{$row->created_at->diffForHumans() }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                        <span class="fas fa-cog"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu custom" role="menu">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#viewImages-{{$row->id}}" title="View Images"><i class="fa fa-eye"></i> View Images</a>

                                        <a class="dropdown-item" href="{{ route('supervisor.product.edit',$row->id)}}"> <span aria-hidden="true" class="fa fa-edit"></span> Edit</a>
                                        <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash"></span> Delete</a>

                                    </div>
                                </div>
                                {!! Form::open(['url' => route('supervisor.product.destroy',$row->id),'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]) !!}
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
                        <th>Category Name</th>
                        <th>Product Name</th>
                        <th>Image</th>
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
    @foreach($data as $key => $row)
        <div class="modal fade" id="viewImages-{{$row->id}}">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Images Product Name {{ $row->name }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @foreach($row->images()->get() as $image)
                                <div class="col-3" id="imageId_{{$image->id}}">
                                    <div class="form-group">
                                        <img src="{{  $image->fullFilePath }}" class="image-preview" height="100px" width="100px">
                                        <br>
                                        <br>
                                        <button id="deleteImages" type="button" data-image="{{ $image->id }}"
                                                data-product="{{ $row->id }}" class="btn btn-danger">Remove
                                        </button>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @endforeach

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
                    {!! Form::open(['url'=> route('supervisor.product.multiple-delete'),'method'=>'POST','id'=>'form_delete']) !!}
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

@stop

@section('before_js')

    @include('layouts.partials.datatables.js')
@stop

@push('js')
    <script>

        $(document).on('click', '#deleteImages', function () {
            var imageId = $(this).data("image");
            var productId = $(this).data("product");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('supervisor.product.delete-images') }}",
                method: 'POST',
                data: {
                    'product_id': productId,
                    'file_id': imageId
                },
                cache: false,
                dataType: "json",

                success: function (response) {
                    if (response.success == true) {
                        $("#imageId_" + imageId).remove();
                        alertify.success(response.message).delay(10000);
                    } else {
                        alertify.error(response.message).delay(10000);
                    }
                },

            });

        });

        $("#del_btn").on("click", function () {
            var id = $(this).data("submit");
            $("#form_" + id).submit();
        });

        $('#myModal').on('show.bs.modal', function (e) {
            var id = e.relatedTarget.dataset.id;
            $("#del_btn").attr("data-submit", id);
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


