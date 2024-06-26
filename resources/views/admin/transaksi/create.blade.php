@extends('admin.layouts.app')
@section('content')
<section class="content-header">                    
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Purchase</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('transaksi.index') }}" class="btn btn-warning">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">                             
                <form action="{{ route('transaksi.store')}}" method="post" >
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="nama_resep">Name</label>
                                <input type="text" name="nama_resep" id="nama_resep" class="form-control" placeholder="Name">   
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="qty">Qty</label>
                                <input type="text" name="qty" id="qty" class="form-control" placeholder="Qty">   
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 col-12">
                            <div class="form-gorup">
                                <label for="id_item">Item</label>
                                <select class="form-control" id="id_item">
                                    @foreach ($daftars as $daftar)
                                        <option value="{{$daftar->id}}" data-nama="{{$daftar->name}}" data-harga="{{$daftar->hpp}}" data-id="{{$daftar->id}}">{{$daftar->name}} Rp. {{number_format($daftar->hpp)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-gorup">
                                <label for="">&nbsp;</label>
                                <button type="button" class="btn btn-success d-block" onclick="tambahItem()">Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Item</th>
                                        <th>Harga</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="transaksiItem">
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <th class="totalHarga">0</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="total_harga" value="0">
                            <button class="btn" style="background: #dbb143; color: white">Create</button>
                        </div>
                    </div>
                </form> 
            </div>                          
        </div>
    </div>
    <!-- /.card -->
</section>
@endsection
@section('customJs')
<script>
    var totalHarga = 0;
    var listItem = [];

    function tambahItem() {
        updateTotalHarga(parseInt($('#id_item').find(':selected').data('harga')));
        var item = listItem.filter((el) => el.id_item === $('#id_item').find(':selected').data('id'));
        if (item.length > 0) {

        } else {
            var item = {
                id_item: $('#id_item').find(':selected').data('id'),
                nama: $('#id_item').find(':selected').data('nama'),
                harga: $('#id_item').find(':selected').data('harga'),
            }
            listItem.push(item)
        }
        updateTable()
    }

    function updateTable() {
        var html = '';
        listItem.map((el,index) => {
            var harga = formatRupiah(el.harga.toString())
            html += `
            <tr>
                <td>${index + 1}</td>
                <td>${el.nama}</td>
                <td>${el.harga}</td>
                <td>
                    <input type="hidden" name="id_item[]" value="${el.id_item}">
                    <button type="button" onclick="deleteItem(${index})" class="btn btn-link">
                        <i class="fas fa-fw fa-trash text-danger"></i>
                    </button>
                </td>
            </tr>
            `
        })
        $('.transaksiItem').html(html)
    }

    function deleteItem(index) {
        var item = listItem[index]
        listItem.splice(index)
        updateTotalHarga(-(item.harga))
        updateTable()
    }


    function updateTotalHarga(nom) {
        totalHarga += nom;
        $('[name=total_harga]').val(totalHarga)
        $('.totalHarga').html(formatRupiah(totalHarga.toString()));

    }

    $("#ItemForm").submit(function(event){
    event.preventDefault();
    var element = $("#ItemForm");
    $("button[type=submit]").prop('disabled',true);
    $.ajax({
        url: '{{ route("item-products.store") }}',
        type: 'post',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled',false);

            if (response["status"] == true) {

                window.location.href="{{ route('item-products.index') }}";
                
                $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                $("#hpp").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#category").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#supplier").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

            } else {
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

                if (errors['hpp']) {
                    $("#hpp").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['hpp']);
                } else {
                    $("#hpp").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['category']) {
                    $("#category").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['category']);
                } else {
                    $("#category").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['supplier']) {
                    $("#supplier").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['supplier']);
                } else {
                    $("#supplier").removeClass('is-invalid')
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