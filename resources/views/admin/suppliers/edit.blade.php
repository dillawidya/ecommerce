@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Supplier</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('suppliers.index') }}" class="btn btn-warning">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" id="editSupplierForm" name="editSupplierForm" method="post">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $supplier->name }}">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address">Address</label>
                                <textarea type="text" name="address" id="address" class="form-control" placeholder="Address">{{ $supplier->address }}</textarea>	
                                <p></p>
                            </div>
                        </div>	
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telp">Telephone</label>
                                <input type="text" name="telp" id="telp" class="form-control" placeholder="Slug" value="{{ $supplier->telp }}">	
                                <p></p>
                            </div>
                        </div>								
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn" style="background: #dbb143; color: white">Update</button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>   
$("#editSupplierForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled',true);
    $.ajax({
        url: '{{ route("suppliers.update", $supplier->id) }}',
        type: 'put',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled',false);

            if (response["status"] == true) {

                window.location.href="{{ route('suppliers.index') }}";


                // $("#name").removeClass('is-invalid')
                //     .siblings('p')
                //     .removeClass('invalid-feedback').html("");

                // $("#slug").removeClass('is-invalid')
                // .siblings('p')
                // .removeClass('invalid-feedback').html("");

            } else {

                if (response['notFound'] == true) {
                    window.location.href = "{{ route('suppliers.index') }}";
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

                if (errors['address']) {
                    $("#address").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['address']);
                } else {
                    $("#address").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['telp']) {
                    $("#telp").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['telp']);
                } else {
                    $("#telp").removeClass('is-invalid')
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