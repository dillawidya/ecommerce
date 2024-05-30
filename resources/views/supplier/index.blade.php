@extends('supplier.layouts.app')

@section('content')
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Produk</h1>
            </div>
            <div class="col-sm-6 text-right">
                {{-- <a href="{{ route('brands.index') }}" class="btn btn-primary">Back</a> --}}
            </div>
        </div>
    </div>
</section>

<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <form action="" id="manageProductForm" name="manageProductForm" method="post">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="nama_produk">Nama Produk</label>
                                <input type="text" name="nama_produk" id="nama_produk" class="form-control" placeholder="Nama Produk">	
                                <p></p>
                            </div>
                        </div>	
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="harga">Harga Per KG</label>
                                <input type="text" name="harga" id="harga" class="form-control" placeholder="Harga">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="qty_awal">Stok(KG)</label>
                                <input type="text" name="qty_awal" id="qty_awal" class="form-control" placeholder="Kuantiti">	
                                <p></p>
                            </div>	
                        </div>
                        <input type="hidden" name="qty_total" id="qty_total" class="form-control" placeholder="Kuantiti">							
                    </div>
                    <button type="submit" class="btn" style="background: #dbb143; color: white">Tambah Produk</button>
                </div>							
            </div>
        </form>
        <div class="card">
            <form action="{{ route('supplier-products.index') }}" method="get">
                <div class="card-header">
                    <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route("supplier-products.index") }}'" class="btn btn-default btn-sm">Reset</button>
                    </div>
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input value="{{ request()->keyword }}" type="text" name="keyword" class="form-control float-right" placeholder="Search">
        
                            <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </form>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap text-center">
                    <tr style="background: #dbb143; color: white">
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Total Stok(KG)</th>
                        <th width="100px">Aksi</th>
                    </tr>
                    @foreach ($supplierProducts as $item)
                        <tr style="background-color: {{ $item->qty_total == 0 ? '#ffcccc' : 'transparent' }};">
                            <td>{{ $item->nama_produk }}</td>
                            <td>Rp. {{ number_format($item->harga)  }}</td>
                            <td>{{ $item->qty_total }}</td>
                            <td>
                                
                                <a href="{{ route('supplier-products.edit', $item->id) }}">
                                    <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('product-manage.edit', $item->id) }}">
                                    <ion-icon name="open-outline" style="color: red"></ion-icon>
                                </a>
                            </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
@endsection

@section('customJs')
<script>
$("#manageProductForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled',true);
    $.ajax({
        url: '{{ route("supplier-products.store") }}',
        type: 'post',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled',false);

            if (response["status"] == true) {

                window.location.href="{{ route('supplier-products.index') }}";

            } else {
                var errors = response['errors'];
                if (errors['nama_produk']) {
                    $("#nama_produk").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['nama_produk']);
                } else {
                    $("#nama_produk").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['harga']) {
                    $("#harga").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['harga']);
                } else {
                    $("#harga").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['qty_awal']) {
                    $("#qty_awal").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['qty_awal']);
                } else {
                    $("#qty_awal").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }
            }

           

        }, error: function(jqXHR, exception){
            console.log("Something went wrong");
        }
    })
});

// function deleteProduct(id) {
//     var url = '';
//     var newUrl = url.replace("ID",id)
//     if (confirm("Yakin ingin menghapus produk?")){
//         $.ajax({
//             url: newUrl,
//             type: 'delete',
//             data: {},
//             dataType: 'json',
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//             success: function(response){
//                 $("button[type=submit]").prop('disabled',false);

//                 if (response["status"]) {

//                     window.location.href="{{ route('supplier-products.index') }}";
//                 } 
//             }
//         });
//     }
// }

</script>
@endsection