@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Items</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('items.index') }}" class="btn btn-warning">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" name="editItemForm" id="editItemForm" method="post">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $Item->name }}">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Supplier</label>
                                <select name="supplier" id="supplier" class="form-control">
                                    <option value="">Pilih Supplier</option>
                                    @if ($suppliers->isNotEmpty())
                                    @foreach ($suppliers as $supplier)
                                    <option {{ ($Item->supplier_id == $supplier->id) ? 'selected' : '' }} value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <p></p>	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="price">Price</label>
                                <input type="number" name="hpp" id="hpp" class="form-control" value="{{ $Item->hpp }}">	
                                <p></p>
                            </div>
                        </div>									
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn" style="background: #dbb143; color: white">Update</button>
                <a href="{{ route('items.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
$("#editItemForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled',true);
    $.ajax({
        url: '{{ route("items.update", $Item->id_item) }}',
        type: 'put',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled',false);

            if (response["status"] == true) {

                window.location.href="{{ route('items.index') }}";


                // $("#name").removeClass('is-invalid')
                //     .siblings('p')
                //     .removeClass('invalid-feedback').html("");

                // $("#slug").removeClass('is-invalid')
                // .siblings('p')
                // .removeClass('invalid-feedback').html("");

            } else {

                if (response['notFound'] == true) {
                    window.location.href = "{{ route('items.index') }}";
                }

                var errors = response['errors'];

                if (errors['name']) {
                    $("#name").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['name']);
                } else {
                    $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['hpp']) {
                    $("#hpp").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['slug']);
                } else {
                    $("#hpp").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['category']) {
                    $("#category").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['category']);
                } else {
                    $("#category").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['supplier']) {
                    $("#supplier").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['supplier']);
                } else {
                    $("#supplier").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }
            }

           

        }, error: function(jqXHR, exception){
            console.log("Something went wrong");
        }
    })
});

$("#name").change(function(){
    element = $(this);
    $("button[type=submit]").prop('disabled',true);
    $.ajax({
        url: '{{ route("getSlug") }}',
        type: 'get',
        data: {title: element.val()},
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled',false);
            if (response["status"] == true) {
                $("#slug").val(response["slug"]);
            }
        }
    }); 
});
</script>
@endsection