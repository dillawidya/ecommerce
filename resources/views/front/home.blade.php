@extends('front.layouts.app')

@section('content')
<section class="section-1">
    <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="false">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <!-- <img src="images/carousel-1.jpg" class="d-block w-100" alt=""> -->

                <picture>
                    <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/c1.png') }}" />
                    <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/c1.png') }}" />
                    <img src="{{ asset('front-assets/images/c1.png') }}" alt="" />
                </picture>

                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3">
                        <h1 class="display-4 text-white mb-3">FOODS</h1>
                        <p class="mx-md-5 px-5">Jelajahi kelezatan dunia di ujung jari Anda! Dari masakan tradisional hingga kreasi modern</p>
                        <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop') }}">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                
                <picture>
                    <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/c2.png') }}" />
                    <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/c2.png') }}" />
                    <img src="{{ asset('front-assets/images/c2.png') }}" alt="" />
                </picture>

                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3">
                        <h1 class="display-4 text-white mb-3">DRINKS</h1>
                        <p class="mx-md-5 px-5">Jelajahi kelezatan dunia di ujung jari Anda! Dari masakan tradisional hingga kreasi modern</p>
                        <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop') }}">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <!-- <img src="images/carousel-3.jpg" class="d-block w-100" alt=""> -->
                
                <picture>
                    <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/c3.png') }}" />
                    <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/c3.png') }}" />
                    <img src="{{ asset('front-assets/images/c3.png') }}" alt="" />
                </picture>

                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3">
                        <h1 class="display-4 text-white mb-3">CAKES</h1>
                        <p class="mx-md-5 px-5">Jelajahi kelezatan dunia di ujung jari Anda! Dari masakan tradisional hingga kreasi modern</p>
                        <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop') }}">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>
<section class="section-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="box shadow-lg box-home">
                    <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0 text-color">Quality Product</h5>
                </div>                    
            </div>
            <div class="col-lg-3 ">
                <div class="box shadow-lg">
                    <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">24/7 Shipping</h2>
                </div>                    
            </div>
            <div class="col-lg-3">
                <div class="box shadow-lg">
                    <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">Fast Delivery</h2>
                </div>                    
            </div>
            <div class="col-lg-3 ">
                <div class="box shadow-lg">
                    <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">24/7 Support</h5>
                </div>                    
            </div>
        </div>
    </div>
</section>
<section class="section-3">
    <div class="container">
        <div class="section-title">
            <h2>Categories</h2>
        </div>           
        <div class="row pb-3">
            @if (getCategories()->isNotEmpty())
            @foreach (getCategories() as $category)
            <div class="col-lg-4">
                <div class="cat-card">
                    <div class="left">
                        @if ($category->image != "")
                        <img src="{{ asset('uploads/category/'.$category->image) }}" alt="" class="img-fluid">

                        @endif
                        {{-- <img src="{{ asset('front-assets/images/cat1.png') }}" alt="" class="img-fluid"> --}}
                    </div>
                    <div class="right">
                        <div class="cat-data">
                            <h2>{{ $category->name }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</section>

<section class="section-4 pt-5">
    <div class="container">
        <div class="section-title">
            <h2>Featured Products</h2>
        </div>    
        <div class="row pb-3">
            @if ($featuredProducts->isNotEmpty())
                @foreach ($featuredProducts as $product)
                <div class="col-md-3 mb-4">
                    <div class="card product-card h-100">
                        <div class="product-image position-relative">
                            <a href="{{ route("front.product", $product->id) }}" class="product-img">
                                @if (!empty($product->image))
                                    <img class="card-img-top" src="{{ asset('uploads/product/'.$product->image) }}">
                                @else
                                    <img src="{{ asset('admin-assets/img/default-150x150.png'.$product->image) }}">
                                @endif
                            </a>
                            <a onclick="addToWishList({{ $product->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>         
                            <div class="product-action">
                                @if ($product->track_qty == 'Yes')
                                    @if ($product->qty > 0)
                                    <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                    </a>
                                    @else
                                    <a class="btn btn-dark" href="javascript:void(0);">
                                        Out Of Stock
                                    </a>
                                    @endif
                                @else
                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
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
            @endif             
        </div>
    </div>
</section>

<section class="section-4 pt-5">
    <div class="container">
        <div class="section-title">
            <h2>Latest Produsts</h2>
        </div>    
        <div class="row pb-3">
            @if ($latestProducts->isNotEmpty())
                @foreach ($latestProducts as $product)
                <div class="col-md-3 mb-4">
                    <div class="card product-card h-100">
                        <div class="product-image position-relative">
                            <a href="{{ route("front.product", $product->id) }}" class="product-img" >
                                @if (!empty($product->image))
                                    <img class="card-img-top" src="{{ asset('uploads/product/'.$product->image) }}">
                                @else
                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                @endif
                            </a>

                            <a onclick="addToWishList({{ $product->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>         

                            <div class="product-action">
                                @if ($product->track_qty == 'Yes')
                                    @if ($product->qty > 0)
                                    <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                    </a>
                                    @else
                                    <a class="btn btn-dark" href="javascript:void(0);">
                                        Out Of Stock
                                    </a>
                                    @endif
                                @else
                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>
                                @endif                           
                            </div>
                        </div>                        
                        <div class="card-body text-center mt-3">
                                <a class="h6 link" href="product.php">{{ $product->titleName }}</a>
                            <div class="price mt-2">

                                <span class="h5"><strong>Rp. {{ number_format($product->price) }}</strong></span>
                                @if ($product->compare_price > 0)
                                <span class="h6 text-underline"><del>Rp. {{ number_format($product->compare_price) }}</del></span>
                                @endif
                            </div>
                        </div>                        
                    </div>                                               
                </div>
                @endforeach
            @endif        
        </div>
    </div>
</section>
@endsection
