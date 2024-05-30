@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Shipping Management</h1>
            </div>
            <div class="col-sm-6 text-right">
                {{-- <a href="{{ route('categories.index') }}" class="btn btn-warning">Back</a> --}}
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
        <form action="" method="post" id="shippingForm" name="shippingForm">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <select name="city" id="city" class="form-control">
                                    <option value="">Select a City</option>
                                    @if ($cities->isNotEmpty())
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                        <option value="rest_of_city">Rest of the City</option>
                                    @endif
                                </select>
                                <p></p>	
                            </div>
                        </div>	
                        <div class="col-md-4">
                            <div class="mb-3">
                                <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <button type="submit" class="btn" style="background: #dbb143; color: white">Create</button>
                            </div>
                        </div>						
                    </div>
                </div>							
            </div> 
        </form>
        <div class="card">
            <div class="card-body">								
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover text-nowrap">
                            <tr style="background: #dbb143; color: white">
                                <th>No</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th width="100px">Action</th>
                            </tr>
                            @if ($shippingCharges->isNotEmpty())
                            @foreach ($shippingCharges as $key => $shippingCharge)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>
                                        {{ ($shippingCharge->city_id == 'rest_of_city') ? 'Rest of the City' : $shippingCharge->name }}
                                    </td>
                                    <td>Rp. {{ number_format($shippingCharge->amount) }}</td>
                                    <td>
                                        <a href="{{ route('shipping.edit',$shippingCharge->id) }}">
                                            <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                            </svg>
                                        </a>
                                        <a href="javascript:void(0);" onclick="deleteRecord({{ $shippingCharge->id }});" class="text-danger w-4 h-4 mr-1">
                                            <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
$("#shippingForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled',true);
    $.ajax({
        url: '{{ route("shipping.store") }}',
        type: 'post',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled',false);

            if (response["status"] == true) {

                window.location.href="{{ route('shipping.create') }}";

            } else {
                var errors = response['errors'];
                if (errors['city']) {
                    $("#city").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['city']);
                } else {
                    $("#city").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['amount']) {
                    $("#amount").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['amount']);
                } else {
                    $("#amount").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }
            }

           

        }, error: function(jqXHR, exception){
            console.log("Something went wrong");
        }
    })
});


function deleteRecord(id) {
        var url = '{{ route("shipping.delete","ID") }}';
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

                        window.location.href="{{ route('shipping.create') }}";
                    } 
                }
            });
        }
    }


    
</script>
@endsection