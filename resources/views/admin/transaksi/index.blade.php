@extends('admin.layouts.app')
@section('content')
<section class="content-header">                    
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Purchases List</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('transaksi.create') }}" class="btn" style="background: #dbb143; color: white">New Purchase</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
    <div class="card">                            
            <div class="card-body table-responsive p-0">                                
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr style="background: #dbb143; color: white">
                            <th>No</th>
                            <th>Date</th>
                            <th>Recipe Name</th>
                            <th>Item Details</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no_transaksi = 1?>
                        @foreach ($transaksis as $transaksi)
                            <tr>
                                <td>{{$no_transaksi}}</td>
                                {{-- <td>{{$transaksi->created_at->toDayDateTimeString() }}</td> --}}
                                <td>{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d M, Y') }}</td>
                                <td>{{$transaksi->nama_resep}}</td>
                                <td>
                                    
                                        @foreach ($transaksi->itemProducts as $item)   
                                            <li>{{$item->nama}}</li>
                                        @endforeach
                                    
                                </td>
                                <td>Rp. {{number_format($transaksi->total_harga)}}</td>
                                <td>{{ $transaksi->qty }}</td>
                                <td>Rp. {{number_format($transaksi->grand_total)}}</td>
                                <td>
                                    <form action="{{ route('transaksi.destroy',$transaksi->id)}}" method="post" class="d-inline">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-sm btn-link text-danger">
                                            <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php $no_transaksi++?>
                        @endforeach
                    </tbody>
                </table>                            
            </div>                         
        </div>
    </div>
    <!-- /.card -->
</section>
@endsection
