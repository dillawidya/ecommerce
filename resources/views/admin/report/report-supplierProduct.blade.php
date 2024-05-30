@extends('admin.layouts.app')

@section('content')
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Report</h1>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="{{ route('report-purchases.reportPurchase') }}"  method="get">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">	
                                <p></p>
                            </div>
                        </div>									
                    </div>
                    <button type="submit" class="btn mr-2" style="background: #dbb143; color: white">Apply</button>
                </div>						
            </div>
        </form>

        <div class="col-lg-12 col-6">
            <div class="small-box text-center">
                <div class="inner">
                <h3>Rp. {{ number_format($total) }}</h3>
                <p>Grand Total</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <a href="javascript:void(0);" class="small-box-footer bg-dark">&nbsp;</a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body table-responsive p-0">								
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr style="background: #dbb143; color: white">
                            <th>Date</th>
                            <th>Supplier Name</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th width="100px">Total Price</th>
                        </tr>
                    </thead> 
                    <tbody>
                        @foreach ($report as $item)
                        <tr>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->nama_produk }}</td>
                            <td>{{ $item->qty_beli }}</td>
                            <td>Rp. {{ number_format($item->harga) }}</td>
                            <td>Rp. {{ number_format($item->total_price) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>										
            </div>
        </div>
    
    </div>
    <!-- /.card -->
</section>
@endsection