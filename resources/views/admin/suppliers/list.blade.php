@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Product Suppliers</h1>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <div class="card">
            <form action="" method="get">
                <div class="card-header">
                    <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route("suppliers.index") }}'" class="btn btn-default btn-sm">Reset</button>
                    </div>
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="form-control float-right" placeholder="Search">
        
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
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr style="background: #dbb143; color: white">
                            <th width="60">No</th>
                            <th>Supplier Name</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead> 
                    <tbody>
                        @if ($suppliers->isNotEmpty())
                            @foreach ($suppliers as $key => $supplier)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $supplier->user->name }}</td>
                                    <td>{{ $supplier->nama_produk }}</td>
                                    <td>Rp. {{ number_format($supplier->harga) }}</td>
                                    <td>{{ $supplier->qty_total }}</td>
                                    <td align="center">
                                        @if($supplier->qty_total > 0)
                                            <a href="{{ route('suppliers.edit', $supplier->id) }}">
                                                <ion-icon name="open-outline"></ion-icon>
                                            </a>
                                        @else
                                            <button class="btn btn-secondary" disabled>
                                                <ion-icon name="open-outline"></ion-icon>
                                            </button>
                                        @endif
                                        
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">Data Tidak Ditemukan</td>
                            </tr>
                        @endif

                       
                       
                    </tbody>
                </table>										
            </div>
            <div class="card-footer clearfix">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
    function deleteSupplier(id) {
        var url = '{{ route("suppliers.delete","ID") }}';
        var newUrl = url.replace("ID",id)
        if (confirm("Are You Sure Want To Delete?")){
            $.ajax({
                url: newUrl,
                type: 'delete',
                data: {},
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response){
                    $("button[type=submit]").prop('disabled',false);

                    if (response["status"]) {

                        window.location.href="{{ route('suppliers.index') }}";
                    } 
                }
            });
        }
    }
</script>

@endsection