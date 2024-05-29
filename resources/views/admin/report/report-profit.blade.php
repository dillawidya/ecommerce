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
        <form action="{{ route('report-profits.reportProfit') }}"  method="get">
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
        <div class="row">
            {{-- <div class="col-md-3">
                <div class="mb-3">
                    <label for="periode">Periode</label>
                    <p class="form-control">{{ $startDate }} - {{ $endDate }}</p>
                </div>
            </div> --}}
            <div class="col-lg-4 col-6">							
                <div class="small-box card">
                    <div class="inner">
                        <h3>Rp. {{ number_format($totalPurchase) }}</h3>
                        <p>Total Purchases</p>
                    </div>
                    <div class="small-box card" style="border-radius: 0px">
                        <a href="javascript:void(0);" class="small-box-footer" style="background: red">&nbsp;</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">							
                <div class="small-box card">
                    <div class="inner">
                        <h3>Rp. {{ number_format($totalOrder) }}</h3>
                        <p>Total Sales</p>
                    </div>
                    <div class="small-box card" style="border-radius: 0px">
                        <a href="javascript:void(0);" class="small-box-footer" style="background: yellow">&nbsp;</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">							
                <div class="small-box card">
                    <div class="inner">
                        <h3>Rp. {{ number_format($profit) }}</h3>
                        <p>Profit</p>
                    </div>
                    <div class="small-box card" style="border-radius: 0px">
                        <a href="javascript:void(0);" class="small-box-footer" style="background: green">&nbsp;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row p-2">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body table-responsive p-0">								
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr style="background: #dbb143; color: white">
                                <th>Purchases Date</th>
                                <th>Total Purchases</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->created_at->format('d/m/Y') }}</td>
                                    <td>Rp. {{ number_format($purchase->grand_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>  
        </div> 

        <div class="col-md-6">
            <div class="card">
                <div class="card-body table-responsive p-0">								
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr style="background: #dbb143; color: white">
                                <th>Sale Date</th>
                                <th>Total Sale</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>Rp. {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>  
        </div> 
    </div>
    <!-- /.card -->
</section>
@endsection