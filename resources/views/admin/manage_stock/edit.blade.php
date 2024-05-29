@extends('admin.layouts.app')
@section('content')
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Manage Stock</h1>
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
        <form action="" method="post" id="editManageStockForm" name="editManageStockForm">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="stock">Stock</label>
                                <input type="text" value="{{ $manageStock->stock }}" name="stock" id="stock" class="form-control" placeholder="Stock">	
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
$("#editManageStockForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled',true);
    $.ajax({
        url: '{{ route("manage-stocks.update", $manageStock->id) }}',
        type: 'put',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled',false);

            if (response["status"] == true) {

                window.location.href="{{ route('manage-stocks.index') }}";

            } else {

                if (response['notFound'] == true) {
                    window.location.href = "{{ route('manage-stocks.index') }}";
                }

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