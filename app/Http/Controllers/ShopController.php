<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductRating;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function index(Request $request) {

        $categoriesArray = [];

        $products = Product::select('products.*', 'transaksis.nama_resep as titleName')
                        ->latest('products.id')
                        ->leftJoin('transaksis', 'transaksis.id', '=', 'products.transaksi_id')
                        ->orderBy('id','DESC');

        if (!empty($request->get('category'))) {
            $categoriesArray = explode(',',$request->get('category'));
            $products = $products->whereIn('category_id',$categoriesArray);
        }

        if ($request->get('price_max') != '' && $request->get('price_min') != '' ) {
            if ($request->get('price_max') == 1000000) {
                $products = $products->whereBetween('price',[intval($request->get('price_min')),1000000000]);
            } else {
                $products = $products->whereBetween('price',[intval($request->get('price_min')),intval
                ($request->get('price_max'))]);

            }
        }

        if (!empty($request->get('search'))) {
            // $products = $products->where('category_id',$categoriesArray);
            $products = Product::select('products.*', 'transaksis.nama_resep as titleName')
                                ->latest('products.id')
                                ->leftJoin('transaksis', 'transaksis.id', '=', 'products.transaksi_id')
                                ->where('transaksis.nama_resep','like','%'.$request->get('search').'%');
        }

        // if ($request->get('sort') != '') {
        //     if ($request->get('sort') == 'latest') {
        //         $products = Product::select('products.*', 'transaksis.nama_resep as titleName')
        //                 ->latest('products.id')
        //                 ->leftJoin('transaksis', 'transaksis.id', '=', 'products.transaksi_id')
        //                 ->orderBy('id','DESC');
        //     } else if ($request->get('sort') == 'price_asc'){
        //         $products = Product::select('products.*', 'transaksis.nama_resep as titleName')
        //                 ->latest('products.id')
        //                 ->leftJoin('transaksis', 'transaksis.id', '=', 'products.transaksi_id')
        //                 ->orderBy('price','ASC');
        //     } else {
        //         $products = Product::select('products.*', 'transaksis.nama_resep as titleName')
        //                 ->latest('products.id')
        //                 ->leftJoin('transaksis', 'transaksis.id', '=', 'products.transaksi_id')
        //                 ->orderBy('price','DESC');
        //     }
        // } else {
        //     $products = Product::select('products.*', 'transaksis.nama_resep as titleName')
        //                 ->latest('products.id')
        //                 ->leftJoin('transaksis', 'transaksis.id', '=', 'products.transaksi_id')
        //                 ->orderBy('id','DESC');
        // }
                        

        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        
        $products = $products->paginate(6);
        $data['categories'] = $categories;
        $data['products'] = $products;
        $data['categoriesArray'] = $categoriesArray;
        $data['priceMax'] = (intval($request->get('price_max')) == 0) ? 1000000 : $request->get('price_max');
        $data['priceMin'] = intval($request->get('price_min'));

        
        return view('front.shop', $data);
    }

    public function product($id) {
        
        $product = Product::select('products.*', 'transaksis.nama_resep as titleName')
                    ->latest('products.id')
                    ->leftJoin('transaksis', 'transaksis.id', '=', 'products.transaksi_id')
                    ->where('products.id', $id) // Ganti $productId dengan nilai ID yang ingin Anda cari
                    ->withCount('product_ratings')
                    ->withSum('product_ratings','rating')
                    ->with(['product_ratings'])
                    ->first();
        //dd($product);

        if ($product == null) {
            abort(404);
        }

        $relatedProducts = [];
        //Fetch Related Product 
        if ($product->related_products != '') {
            $productArray = explode(',',$product->related_products);
            $relatedProducts = Product::leftJoin('transaksis', 'products.transaksi_id', '=', 'transaksis.id')
                 ->select('products.*', 'transaksis.nama_resep as titleName')
                 ->latest('products.id')
                 ->whereIn('products.id',$productArray)
                 ->where('status',1)
                 ->get();
            // $relatedProducts = Product::whereIn('id',$productArray)->get();
        }

        $data['product'] = $product;
        $data['relatedProducts'] = $relatedProducts;

        //Rating calculate
        // "product_ratings_count" => 1
        // "product_ratings_sum_rating" => 5.0

        $avgRating= '0.00';
        $avgRatingPer= 0;
        if ($product->product_ratings_count > 0) {
            $avgRating = number_format(($product->product_ratings_sum_rating/$product->product_ratings_count),2);
            $avgRatingPer = ($avgRating*100)/5;
        }

        $data['avgRating'] = $avgRating;
        $data['avgRatingPer'] = $avgRatingPer;

        return view('front.product',$data);
    }

    public function saveRating($id, Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5',
            'email' => 'required|email',
            'comment' => 'required|min:10',
            'rating' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors() 
            ]);
        }

        // $count = ProductRating::where('email',$request->email)->count();

        // if ($count > 0) {

        //     session()->flash('error','You Already Rating This Product');

        //     return response()->json([
        //         'status' => true
        //     ]);
        // }

        $productRating = new ProductRating;
        $productRating->product_id = $id;
        $productRating->username = $request->name;
        $productRating->email = $request->email;
        $productRating->rating = $request->rating;
        $productRating->comment = $request->comment;
        $productRating->status = 0;
        $productRating->save();

        session()->flash('success','Thanks For Your Rating');

        return response()->json([
            'status' => true,
            'message' => 'Thanks For Your Rating'
        ]);
    }
}
