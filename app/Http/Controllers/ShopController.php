<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

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
                    ->first();

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


        return view('front.product',$data);
    }
}
