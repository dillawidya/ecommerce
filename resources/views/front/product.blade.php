@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                
                <li class="breadcrumb-item">{{ $product->titleName }}</li>
                
            </ol>
        </div>
    </div>
</section>

<section class="section-7 pt-3 mb-3">
    <div class="container">
        <div class="row ">
            <div class="col-md-5">
                @if ($product->image)
                    <img class="card-img-top" src="{{ asset('uploads/product/'.$product->image) }}">
                @endif
            </div>

            <div class="col-md-7">
                <div class="bg-light right">
                    <h1>{{ $product->titleName }}</h1>
                    <div class="d-flex mb-3">
                        <div class="text-primary mr-2">
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star-half-alt"></small>
                            <small class="far fa-star"></small>
                        </div>
                        <small class="pt-1">(99 Reviews)</small>
                    </div>
                    @if ($product->compare_price > 0)
                        <h2 class="price text-secondary"><del>Rp. {{ number_format($product->compare_price) }}</del></h2>
                    @endif
                    <h2 class="price ">Rp. {{ number_format($product->price) }}</h2>

                    {!! $product->story !!}

                    {{-- <a href="javascript:void(0);" onclick="addToCart({{ $product->id }});" class="btn btn-dark"><i class="fas fa-shopping-cart"></i> &nbsp;ADD TO CART</a> --}}
                    @if ($product->track_qty == 'Yes')
                        @if ($product->qty > 0)
                        <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                            <i class="fa fa-shopping-cart"></i> &nbsp;Add To Cart
                        </a>
                        @else
                        <a class="btn btn-dark" href="javascript:void(0);">
                            Out Of Stock
                        </a>
                        @endif
                    @else
                    <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                        <i class="fa fa-shopping-cart"></i> &nbsp;Add To Cart
                    </a>
                    @endif  
                </div>
            </div>

            <div class="col-md-12 mt-5">
                <div class="bg-light">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="false">How To Make</button>
                        </li>
                        {{-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
                        </li> --}}
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            {!! $product->description !!}
                        </div>
                        <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                        {!! $product->make !!}
                        </div>
                        {{-- <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab"> --}}
                        
                        </div>
                    </div>
                </div>
            </div> 
        </div>           
    </div>
</section>

@if (!empty($relatedProducts))

<section class="section-8 pt-5">
    <div class="container">
        <div class="section-title">
            <h2>Related Products</h2>
        </div>    
        <div class="row pb-3">
                @foreach ($relatedProducts as $relProduct)
                <div class="col-md-3 mb-4">
                    <div class="card product-card h-100">
                        <div class="product-image position-relative">
                            <a href="#" class="product-img">
                                @if (!@empty($relProduct->image))
                                    <img class="card-img-top" src="{{ asset('uploads/product/'.$relProduct->image) }}" >
                                @else
                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                @endif
                            </a>
                            <a onclick="addToWishList({{ $product->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>         
                            <div class="product-action">
                                @if ($relProduct->track_qty == 'Yes')
                                    @if ($relProduct->qty > 0)
                                    <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $relProduct->id }});">
                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                    </a>
                                    @else
                                    <a class="btn btn-dark" href="javascript:void(0);">
                                        Out Of Stock
                                    </a>
                                    @endif
                                @else
                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $relProduct->id }});">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>
                                @endif                              
                            </div>
                        </div>                        
                        <div class="card-body text-center mt-3">
                                <a class="h6 link" href="product.php">{{ $product->titleName }}</a>
                            <div class="price mt-2">

                                <span class="h5"><strong>Rp. {{number_format ($product->price) }}</strong></span>
                                @if ($product->compare_price > 0)
                                <span class="h6 text-underline"><del>Rp. {{number_format ($product->compare_price) }}</del></span>
                                @endif
                            </div>
                        </div>                        
                    </div>                                               
                </div>
                @endforeach
        </div>
    </div>
</section>
@endif             
@endsection

@section('customJs')
@endsection