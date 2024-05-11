<?php

namespace App\Http\Controllers\admin;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index(Request $request) {
        $suppliers = Supplier::latest('id');

        if ($request->get('keyword')) {
            $suppliers = $suppliers->where('name','like','%'.$request->keyword.'%');
        }

        $suppliers = $suppliers->paginate(10);

        return view('admin.suppliers.list',compact('suppliers'));
    }

    public function create() {
        return view('admin.suppliers.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'address' => 'required',
            'telp' => 'required'
        ]);

        if ($validator->passes()) {
            $supplier = new Supplier();
            $supplier->name = $request->name;
            $supplier->address = $request->address;
            $supplier->telp = $request->telp;
            $supplier->save();

            $request->session()->flash('success','Supplier Berhasil Ditambahkan.');
            return response()->json([
                'status' => true,
                'message' => 'Supplier Berhasil Ditambahkan.'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request) {
        $supplier = Supplier::find($id);

        if (empty($supplier)) {
            $request->session()->flash('error', 'Data Tidak Ditemukan');
            return redirect()->route('suppliers.index');
        }

        $data['supplier'] = $supplier;
        return view('admin.suppliers.edit',$data);
    }

    public function update($id, Request $request) {

        $supplier = Supplier::find($id);

        if (empty($supplier)) {
            $request->session()->flash('error', 'Data Tidak Ditemukan');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'address' => 'required',
            'telp' => 'required'
        ]);

        if ($validator->passes()) {

            $supplier->name = $request->name;
            $supplier->address = $request->address;
            $supplier->telp = $request->telp;
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

    public function destroy($id, Request $request) {
        $supplier = Supplier::find($id);

        if (empty($supplier)) {
            $request->session()->flash('error', 'Data Tidak Ditemukan');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $supplier->delete();

        $request->session()->flash('success','Supplier Berhasil DiHapus.');

            return response([
                'status' => true,
                'message' => 'Supplier Berhasil DiHapus.'
            ]);
    }
}
