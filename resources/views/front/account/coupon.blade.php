@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                {{-- <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">My Account</a></li>
                <li class="breadcrumb-item">My Orders</li> --}}
                <li class="breadcrumb-item" style="color: #dbb143;"><i class="fas fa-home" style="margin-right: 5px"></i>My Coupons</li>

            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div class="col-md-3">
                @include('front.account.common.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">My Coupons</h2>
                    </div>
                    <div class="container mt-3">
                        <div class="voucher-list">
                            @foreach ($coupons as $coupon)
                            <div class="voucher-card">
                                <img src="{{ asset('front-assets/images/coupon.jpg') }}" alt="Voucher Image">
                                <div class="voucher-details">
                                    <h3>{{ $coupon->description }}</h3>
                                    <p>Discount: @if ($coupon->type == 'percent')
                                                    {{ $coupon->discount_amount }}%
                                                @else
                                                    Rp. {{ number_format($coupon->discount_amount) }}
                                                @endif
                                    </p>
                                    <p>Min. Rp.{{ number_format($coupon->min_amount) }} </p>
                                    <p class="voucher-code">{{ $coupon->code }}</p>
                                    <div class="voucher-footer">
                                        Expired at: 
                                        {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d M, Y')  }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table">
                                <thead> 
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Min Amount</th>
                                        <th>Discount</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($coupons as $coupon)
                                        <tr>
                                            <td>
                                                <span class="badge bg-warning">{{ $coupon->code }}</span>
                                                </td>
                                            <td>{{ $coupon->name }}</td>
                                            <td>Rp. {{ number_format($coupon->min_amount) }}</td>
                                            <td>
                                                @if ($coupon->type == 'percent')
                                                    {{ $coupon->discount_amount }}%
                                                @else
                                                    Rp. {{ number_format($coupon->discount_amount) }}
                                                @endif
                                            </td>
                                            <td>{{ $coupon->starts_at }}</td>
                                            <td>{{ $coupon->expires_at }}</td>
                                        </tr>
                                    @endforeach                                     
                                </tbody>
                            </table>
                        </div> 
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection