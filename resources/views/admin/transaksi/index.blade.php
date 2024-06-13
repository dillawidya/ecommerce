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
                            <th width="100px">Action</th>
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
                                    <a href="#" onclick="editQty({{ $transaksi->id }})">
                                        <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </a>
                                    <a href="#" onclick="deletePurchase({{ $transaksi->id }})" class="text-danger w-4 h-4 mr-1">
                                        <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            <?php $no_transaksi++?>
                        @endforeach
                    </tbody>
                </table>                            
            </div>                         
        </div>
    </div>
    <!-- Modal for Editing Quantity -->
    <div class="modal fade" id="editQtyModal" tabindex="-1" role="dialog" aria-labelledby="editQtyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="editQtyModalLabel">Edit Quantity</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <form id="editQtyForm">
                <input type="hidden" id="editTransaksiId" name="id">
                <div class="form-group">
                <label for="newQty">New Quantity</label>
                <input type="number" class="form-control" id="newQty" name="qty" required>
                </div>
            </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="updateQty()">Save changes</button>
            </div>
        </div>
        </div>
    </div>
  
    <!-- /.card -->
</section>
@endsection
@section('customJs')
<script>
    function deletePurchase(id) {
        var url = '{{ route("transaksi.destroy","ID") }}';
        var newUrl = url.replace("ID",id)
        if (confirm("Are You Sure Want to Delete?")){
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

                        window.location.href="{{ route('transaksi.index') }}";
                    } 
                }
            });
        }
    }

    function editQty(id) {
        // Tampilkan modal dan isi dengan data transaksi yang dipilih
        $('#editTransaksiId').val(id);
        $('#newQty').val('');
        $('#editQtyModal').modal('show');
    }

    function updateQty() {
        var id = $('#editTransaksiId').val();
        var qty = $('#newQty').val();

        if (!qty || qty <= 0) {
            alert("Please enter a valid quantity.");
            return;
        }

        var url = '{{ route("transaksi.update", "ID") }}';
        var newUrl = url.replace("ID", id);

        $.ajax({
            url: newUrl,
            type: 'post',
            data: { qty: qty },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response["status"]) {
                    // Tutup modal dan refresh halaman
                    $('#editQtyModal').modal('hide');
                    window.location.href = "{{ route('transaksi.index') }}";
                } else {
                    alert("Failed to update quantity.");
                }
            },
            error: function(jqXHR, exception) {
                alert("Something went wrong");
            }
        });
    }
</script>
@endsection
