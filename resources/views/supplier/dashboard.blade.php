@extends('supplier.layouts.app')

@section('content')
{{-- <section class="content-header">					
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section> --}}
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        {{-- <div class="row">
            <h1 class="h1 text-bold mt-2">Selamat Datang, </h1><i class="h2 mt-3 ml-2 mb-4">{{ $supplier->name }}</i>
        </div> --}}
        <div class="row">

            <div class="col-lg-3 col-6 mt-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                    <h3>{{ $supplierProductCount }}</h3>
                    <p>Total Produk</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <a href="{{ route('supplier-products.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6 mt-3">
                <div class="small-box bg-success">
                    <div class="inner">
                    <h3>Rp. {{ number_format($totalPendapatan) }}</h3>
                    <p>Total Pendapatan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
                </div>
            </div>
        </div>

    </div>					
    <!-- /.card -->
</section>
@endsection

           
			
