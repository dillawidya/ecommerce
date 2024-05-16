@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                {{-- <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Shop</li> --}}
                <li class="breadcrumb-item" style="color: #dbb143;"><i class="fas fa-home" style="margin-right: 5px"></i>Shop</li>

            </ol>
        </div>
    </div>
</section>

<section class="section-6 pt-5">
    <div class="container">
        <div class="row">            
            <div class="col-md-3 sidebar">
                <div class="sub-title mt-5">
                    <h2>Categories</h3>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        @if ($categories->isNotEmpty())
                            @foreach ($categories as $category)
                        <div class="form-check mb-2">
                            <input {{ (in_array($category->id, $categoriesArray)) ? 'checked' : '' }} class="form-check-input category-label" type="checkbox"  name="category[]" value="{{ $category->id }}" id="category-{{ $category->id }}">
                            <label class="form-check-label" for="category-{{ $category->id }}">
                                {{ $category->name }}
                            </label>
                        </div>
                            @endforeach
                        @endif                  
                    </div>
                </div>

                <div class="sub-title mt-5">
                    <h2>Price</h3>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <input type="text" class="js-range-slider" name="my_range" value="" />
               
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-end mb-4">
                            <div class="ml-2">
                                {{-- <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">Sorting</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Latest</a>
                                        <a class="dropdown-item" href="#">Price High</a>
                                        <a class="dropdown-item" href="#">Price Low</a>
                                    </div>
                                </div>                                     --}}
                                {{-- <select name="sort" id="sort" class="form-control">
                                    <option value="latest">Latest</option>
                                    <option value="price_desc">Price High</option>
                                    <option value="price_asc">Price Low</option>
                                </select> --}}
                            </div>
                        </div>
                    </div>
                    @if ($products->isNotEmpty())
                    @foreach ($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card product-card h-100">
                            <div class="product-image position-relative">
                                <a href="{{ route("front.product", $product->id) }}" class="product-img">
                                    @if (!@empty($product->image))
                                        <img class="card-img-top h-100" src="{{ asset('uploads/product/'.$product->image) }}" >
                                    @else
                                        <img src="{{ asset('admin-assets/img/default-150x150.png'.$product->image) }}">
                                    @endif
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
                                    <span class="h5"><strong>Rp. {{number_format($product->price) }}</strong></span>
                                    @if ($product->compare_price > 0)
                                    <span class="h6 text-underline"><del>Rp. {{number_format ($product->compare_price) }}</del></span>
                                    @endif
                                </div>
                            </div>                        
                        </div>                                               
                    </div>  
                    @endforeach
                    @endif
                    <div class="col-md-12 pt-5">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>

    rangeSlider = $(".js-range-slider").ionRangeSlider({
        type: "double",
        min: 0,
        max: 500000,
        from: {{ ($priceMin) }},
        step: 10,
        to: {{ ($priceMax) }},
        skin: "round",
        max_postfix: "+",
        prefix: "Rp",
        onFinish: function() {
            apply_filters()
        }
    });

    var slider = $(".js-range-slider").data("ionRangeSlider");

    $("#sort").change(function(){
        apply_filters();
    });

    $(".category-label").change(function(){
        apply_filters();
    });

    $("#search").change(function(){
        apply_filters();
    });

    function apply_filters(){
        var categories = [];

        $(".category-label").each(function(){
            if ($(this).is(":checked") == true) {
                categories.push($(this).val());
            }
        });

        var url = '{{ url()->current() }}?';

        //category filter
        if (categories.length > 0) {
            url += '&category='+categories.toString()
        }

        //price range
        url += '&price_min='+slider.result.from+'&price_max='+slider.result.to;

        var keyword = $("#search").val();

        if (keyword.length > 0) {
            url += '&search='+keyword;
        }

        // //sortings
        // url += '&sort='+$("#sort").val()

        window.location.href = url;

        
    }

</script>
@endsection