<?php

namespace App\Http\Controllers\admin;

// use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\SupplierProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index(Request $request) {
        $suppliers = SupplierProduct::latest('id')
                    ->with('user');
        
        if ($request->get('keyword')) {

            $suppliers = $suppliers->where('nama_produk','like','%'.$request->keyword.'%');
        }

        $suppliers = $suppliers->paginate(10);

        return view('admin.suppliers.list', compact('suppliers'));
    }

    public function edit($id, Request $request) {
        $supplier = SupplierProduct::find($id);

        $qty_value = $supplier->qty_total > 0 ? $supplier->qty_total : $supplier->qty_awal;

        if (empty($supplier)) {
            $request->session()->flash('error', 'Data Tidak Ditemukan');
            return redirect()->route('suppliers.index');
        }

        $data['supplier'] = $supplier;
        $data['qty_value'] = $qty_value;
        return view('admin.suppliers.edit',$data);
    }

    public function update($id, Request $request) {

        $supplier = SupplierProduct::find($id);

        if (empty($supplier)) {
            $request->session()->flash('error', 'Data Tidak Ditemukan');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $validator = Validator::make($request->all(),[
            'qty_belibaru' => 'required|numeric',
        ]);

        if ($validator->passes()) {

            $max_qty = $supplier->qty_total > 0 ? $supplier->qty_total : $supplier->qty_awal;

            if ($request->qty_belibaru > $max_qty) {

                $request->session()->flash('error','Purchase quantity cannot exceed total quantity');

                return response()->json([
                    'status' => true,
                ]);
            }

            $supplier->qty_beli = $request->qty_belibaru + $supplier->qty_beli;
            $supplier->qty_total = $supplier->qty_total - $request->qty_belibaru;
            $supplier->save();

            $request->session()->flash('success','Supplier Berhasil DiUpdate.');

            return response()->json([
                'status' => true,
                'message' => 'Supplier Berhasil Diupdate.'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        
    }

}
