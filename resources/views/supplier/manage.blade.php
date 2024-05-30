@extends('supplier.layouts.app')
@section('content')
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Produk</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('supplier-products.index') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <form action="" id="editSupplierProductForm" name="editSupplierProductForm" method="post">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_produk">Nama Produk</label>
                                <input type="text" value="{{ $supplierProduct->nama_produk }}" readonly name="nama_produk" id="nama_produk" class="form-control" placeholder="Nama Produk">
                                <p></p>
                            </div>
                        </div>	
                        <input type="hidden" value="{{ $supplierProduct->qty_awal }}" readonly name="qty_awal" id="qty_awal" class="form-control" placeholder="Kuantiti">	
                        <input type="hidden" value="{{ $supplierProduct->qty_beli }}" readonly name="qty_awal" id="qty_awal" class="form-control" placeholder="Kuantiti">	
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="qty_total">Sisa Stok</label>
                                <input type="text" value="{{ $supplierProduct->qty_total }}" readonly name="qty_total" id="qty_total" class="form-control" placeholder="Kuantiti">	
                                <p></p>
                            </div>	
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="harga">Harga Per KG</label>
                                <input type="text" value="{{ $supplierProduct->harga }}" name="harga" id="harga" class="form-control" placeholder="Harga">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="manage_stok">Sesuaikan Stok(KG)</label>
                                <input type="text" name="manage_stok" id="manage_stok" class="form-control" placeholder="Kuantiti">	
                                <p></p>
                            </div>	
                        </div>						
                    </div>
                    <button type="submit" class="btn" style="background: #dbb143; color: white">Edit Produk</button>
                </div>							
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
@endsection
@section('customJs')
<script>
$("#editSupplierProductForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled',true);
    $.ajax({
        url: '{{ route("product-manage.update", $supplierProduct->id) }}',
        type: 'put',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled',false);

            if (response["status"] == true) {

                window.location.href="{{ route('supplier-products.index') }}";


                // $("#name").removeClass('is-invalid')
                //     .siblings('p')
                //     .removeClass('invalid-feedback').html("");

                // $("#slug").removeClass('is-invalid')
                // .siblings('p')
                // .removeClass('invalid-feedback').html("");

            } else {

                if (response['notFound'] == true) {
                    window.location.href = "{{ route('supplier-products.index') }}";
                }

                var errors = response['errors'];
                if (errors['qty_baru']) {
                    $("#qty_baru").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['qty_baru']);
                } else {
                    $("#qty_baru").removeClass('is-invalid')
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