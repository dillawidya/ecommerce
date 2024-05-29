@extends('admin.layouts.app')
@section('content')
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Manage Stock</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('manage-stocks.index') }}" class="btn btn-warning">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="manageStockForm" name="manageStockForm">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Supplier</label>
                                <select name="supplier" id="supplier" class="form-control">
                                    <option value="">Pilih Supplier</option>
                                    @if ($users->isNotEmpty())
                                    @foreach ($users as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <p></p>	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Product Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Product Name">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price">Price /KG</label>
                                <input type="number" name="price" id="price" class="form-control" placeholder="Price">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock">Stock</label>
                                <input type="text" name="stock" id="stock" class="form-control" placeholder="Stock">	
                                <p></p>
                            </div>
                        </div>								
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn" style="background: #dbb143; color: white">Create</button>
                <a href="{{ route('manage-stocks.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
@endsection
@section('customJs')
<script>
    $("#manageStockForm").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled',true);
        $.ajax({
            url: '{{ route("manage-stocks.store") }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled',false);

                if (response["status"] == true) {

                    window.location.href="{{ route('manage-stocks.index') }}";

                    $("#supplier").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                    $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                    $("#price").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                    $("#stock").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                } else {
                    var errors = response['errors'];
                    if (errors['supplier']) {
                        $("#supplier").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['supplier']);
                    } else {
                        $("#supplier").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['name']) {
                        $("#name").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['name']);
                    } else {
                        $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['price']) {
                        $("#price").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['price']);
                    } else {
                        $("#price").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['stock']) {
                        $("#stock").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['stock']);
                    } else {
                        $("#stock").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }
                }

            

            }, error: function(jqXHR, exception){
                console.log("Something went wrong");
            }
        })
    });
</script>
@endsection