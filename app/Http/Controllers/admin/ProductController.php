<?php

namespace App\Http\Controllers\admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\TempImage;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\ProductRating;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::leftJoin('categories', 'products.category_id', '=', 'categories.id')
                 ->leftJoin('transaksis', 'products.transaksi_id', '=', 'transaksis.id')
                 ->select('products.*', 'categories.name as category_name', 'transaksis.nama_resep as titleName')
                 ->latest('products.id');

        // $products = Product::select('products.*', 'transaksis.nama_resep as titleName')
        //                 ->latest('products.id')
        //                 ->leftJoin('transaksis', 'transaksis.id', '=', 'products.transaksi_id');
        
        if (!empty($request->get('keyword'))) {
            $products = $products->where('titleName','like','%'.$request->get('keyword').'%');
        }

        $products = $products->paginate(10);
        return view('admin.products.list',compact('products'));
        // $products = Product::latest('id')->paginate();
        // $data['products'] = $products;

        // return view('admin.products.list', $data);
    }

    Public function create() {
        $categories = Category::all();
        $title = Transaksi::orderBy('created_at','desc')->get();
        return view('admin.products.create', compact('title','categories'));
    }

    public function store(Request $request) {
        $rules = [
            'title' => 'required',
            'price' => 'required|numeric',
            // 'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',

        ];


        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {

            $product = new Product();
            $product->transaksi_id = $request->title;
            $product->story = $request->story;
            $product->description = $request->description;
            $product->make = $request->make;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->category_id = $request->category;
            $product->status = $request->status;
            $product->is_featured = $request->is_featured;
            $product->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
            $product->save();

            //Save Image

            if (!empty($request->image_id)) {

                
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);
                
                $newImageName = $product->id.'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/product/'.$newImageName;
                File::copy($sPath,$dPath);
                
                $product->image = $newImageName;
                $product->save();

                // //Generate Image Thumbnail
                // // $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                // // $img = Image::make($sPath);
                // // $img->resize(450, 600);
                // // $img->fit(800, 600, function ($constraint) {
                // //     $constraint->upsize();
                // // });
                // // $img->save($dPath);

            }

            $request->session()->flash('success','Product Added Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product Added Successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request) {
        $categories = Category::all();
        $product = Product::find($id);

        if (empty($product)) {
            return redirect()->route('products.index')->with('error', 'Product Not Found');
            
        }

        $relatedProducts = [];
        //Fetch Related Product 
        if ($product->related_products != '') {
            $productArray = explode(',',$product->related_products);
            $relatedProducts = Product::leftJoin('transaksis', 'products.transaksi_id', '=', 'transaksis.id')
                 ->select('products.*', 'transaksis.nama_resep as titleName')
                 ->latest('products.id')
                 ->whereIn('products.id',$productArray)->get();
            // $relatedProducts = Product::whereIn('id',$productArray)->get();
        }
        

        $data = [];
        $data['product'] = $product;
        $title = Transaksi::orderBy('created_at','desc')->get();
        $data['title'] = $title;
        $data['categories'] = $categories;
        $data['relatedProducts'] = $relatedProducts;

        return view('admin.products.edit',$data);
    }

    public function update($id, Request $request) {

        $product = Product::find($id);

        $rules = [
            'title' => 'required',
            'price' => 'required|numeric',
            // 'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',

        ];


        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {

            $product->transaksi_id = $request->title;
            $product->story = $request->story;
            $product->description = $request->description;
            $product->make = $request->make;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->category_id = $request->category;
            $product->status = $request->status;
            $product->is_featured = $request->is_featured;
            $product->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/product'), $fileName);
                $product->image = $fileName;
            }
            $product->save();

            //Save Image
            
            
            $request->session()->flash('success','Product Updated Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product Updated Successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request) {
        $product = Product::find($id);

        if (empty($product)) {
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        File::delete(public_path('uploads/product/'. $product->image));

        $product->delete();

        $request->session()->flash('success', 'Product Delete Successfully');

        return response()->json([
            'status' => true,
            'message' => 'Product Delete Successfully'
        ]);

    }

    public function getProducts(Request $request){

        $tempProduct = [];
        if ($request->term != "") {
            $products = Product::leftJoin('transaksis', 'products.transaksi_id', '=', 'transaksis.id')
                 ->select('products.*', 'transaksis.nama_resep as titleName')
                 ->latest('products.id')
                 ->where('nama_resep','like','%'.$request->term.'%')->get();
            // $products = Products::where('name','like','%'.$request->term.'%')->get();

            if ($products != null) {
                foreach ($products as $product) {
                    $tempProduct[] = array('id' => $product->id, 'text' => $product->titleName);
                }
            }
        }

        return response()->json([
            'tags' => $tempProduct,
            'status' => true
        ]);
    }

    public function productRatings(Request $request) {
        $ratings = ProductRating::select('product_ratings.*', 'transaksis.nama_resep as titleName')
        ->orderBy('product_ratings.created_at','DESC')->with('product.transaksi');
        $ratings = $ratings->leftJoin('products','products.id','product_ratings.product_id');
        $ratings = $ratings->leftJoin('transaksis', 'products.transaksi_id', '=', 'transaksis.id');

        
        if ($request->get('keyword') != "") {
            $ratings = $ratings->orWhere('nama_resep','like','%'.$request->keyword.'%');
            $ratings = $ratings->orWhere('product_ratings.username','like','%'.$request->keyword.'%');
        }
        $ratings = $ratings->paginate(10);
        return view('admin.products.ratings',[
            'ratings' => $ratings
        ]);
    }

    public function changeRatingStatus(Request $request) {
        $productRating = productRating::find($request->id);
        $productRating->status = $request->status;
        $productRating->save();

        session()->flash('success','Status Changed Successfully');

        return response()->json([
            'status' => true
        ]);
    }
}
