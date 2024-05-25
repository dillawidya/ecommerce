@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Order to Supplier</h1>
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
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="nama_produk">Supplier Name</label>
                                <input type="text" value="{{ $supplier->user->name }}" readonly name="nama_produk" id="nama_produk" class="form-control" placeholder="Nama Produk">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="nama_produk">Nama Produk</label>
                                <input type="text" value="{{ $supplier->nama_produk }}" readonly name="nama_produk" id="nama_produk" class="form-control" placeholder="Nama Produk">	
                                <p></p>
                            </div>
                        </div>	
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="harga">Harga Per KG</label>
                                <input type="text" value="{{ number_format($supplier->harga) }}" readonly name="harga" id="harga" class="form-control" placeholder="Harga">	
                                <p></p>
                            </div>
                        </div>
                        <input type="hidden" value="{{  $supplier->qty_awal }}" readonly name="qty_awal" id="qty_awal" class="form-control" placeholder="Kuantiti">	
                        <input type="hidden" class="form-control" id="qty_beli" readonly name="qty_beli" value="{{  old('qty_beli',$supplier->qty_beli) }}" required>

                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="qty_awal">Stock Total</label>
                                <input type="text" value="{{  $qty_value }}" readonly name="qty_awal" id="qty_awal" class="form-control" placeholder="Kuantiti">	
                                <p></p>
                            </div>	
                        </div>	
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="qty_belibaru">Kuantiti Beli</label>
                                <input type="text" name="qty_belibaru" id="qty_belibaru" class="form-control" placeholder="Kuantiti">	
                                <p></p>
                            </div>	
                        </div>					
                    </div>
                    <div class="pt-3">
                        <button type="submit" class="btn" style="background: #dbb143; color: white">Save</button>
                        <a href="https://wa.me/{{ $supplier->user->phone }}?text=Tolong%20siapkan%20{{ $supplier->nama_produk }}%20Harga%20{{ number_format($supplier->harga) }}%20Sebanyak%20:%20...%20KG">
                            Order to Whatsapp
                        </a>
                    </div>
                </div>							
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

            } else {

                if (response['notFound'] == true) {
                    window.location.href = "{{ route('suppliers.index') }}";
                }

                var errors = response['errors'];
                if (errors['qty_belibaru']) {
                    $("#qty_belibaru").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['qty_belibaru']);
                } else {
                    $("#qty_belibaru").removeClass('is-invalid')
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