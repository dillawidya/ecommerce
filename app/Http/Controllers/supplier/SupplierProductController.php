<?php

namespace App\Http\Controllers\supplier;

use Illuminate\Http\Request;
use App\Models\SupplierProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupplierProductController extends Controller
{ 
    public function index(Request $request) {
        $user = Auth::user();
        $supplierProducts = $user->supplierProducts()->latest('id');
        
        $qty_total = SupplierProduct::sum('qty_awal');
        

        if ($request->get('keyword')) {

            $supplierProducts->where('nama_produk','like','%'.$request->keyword.'%');
        }

        $supplierProducts = $supplierProducts->get();

        return view('supplier.index', [
            'user' => $user,
            'supplierProducts' => $supplierProducts,
            'qty_total' => $qty_total,
        ]);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(),[
            'nama_produk' => 'required',
            'harga' => 'required|numeric',
            'qty_awal' => 'required'
        ]);

        if ($validator->passes()) {

            
            $supplierProduct = new SupplierProduct();
            $supplierProduct->nama_produk = $request->nama_produk;
            $supplierProduct->harga = $request->harga;
            $supplierProduct->qty_awal = $request->qty_awal;
            $supplierProduct->qty_total =$request->qty_awal;
            $supplierProduct->user_id = Auth::id();
            $supplierProduct->save();

            session()->flash('success','Produk Berhasil Ditambahkan');

            return response()->json([
                'status' => true,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request) {
        $supplierProduct = SupplierProduct::find($id);

        if (empty($supplierProduct)) {
            $request->session()->flash('error', 'Data Tidak Ditemukan');
            return redirect()->route('supplier-products.index');
        }

        $data['supplierProduct'] = $supplierProduct;
        return view('supplier.edit',$data);
    }

    public function update($id, Request $request) {

        $supplierProduct = SupplierProduct::find($id);

        if (empty($supplierProduct)) {
            $request->session()->flash('error', 'Data Tidak Ditemukan');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $validator = Validator::make($request->all(),[
            'qty_baru' => 'required',
        ]);

        

        if ($validator->passes()) {
                
            $supplierProduct->harga = $request->harga;
            $supplierProduct->qty_awal = $request->qty_baru + $supplierProduct->qty_total;
            $supplierProduct->qty_total = $supplierProduct->qty_awal;
            $supplierProduct->save();

            $request->session()->flash('success','Produk Berhasil DiUpdate.');

            return response()->json([
                'status' => true,
                'message' => 'Produk Berhasil Diupdate.'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    // public function destroy($id, Request $request) {
    //     $supplierProduct = SupplierProduct::find($id);

    //     if (empty($supplierProduct)) {
    //         $request->session()->flash('error', 'Data Tidak Ditemukan');
    //         return response([
    //             'status' => false,
    //             'notFound' => true
    //         ]);
    //     }

    //     $supplierProduct->delete();

    //     $request->session()->flash('success','Supplier Berhasil DiHapus.');

    //         return response([
    //             'status' => true,
    //             'message' => 'Supplier Berhasil DiHapus.'
    //         ]);
    // }
}
