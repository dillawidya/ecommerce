<?php

namespace App\Http\Controllers\admin;

use App\Models\Item;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index(Request $request) {
        // $Items = Item::leftJoin('categories', 'items.category_id', '=', 'categories.id')
        //          ->leftJoin('suppliers', 'items.supplier_id', '=', 'suppliers.id')
        //          ->select('items.*', 'categories.name as category_name', 'suppliers.name as supplier_name')
        //          ->latest('items.id_item');
        
        $Items = Item::select('items.*','suppliers.name as supplierName')
                            ->latest('items.id_item')
                            ->leftjoin('suppliers','suppliers.id','items.supplier_id');

        if (!empty($request->get('keyword'))) {
            $Items = $Items->where('items.name','like','%'.$request->get('keyword').'%');
            $Items = $Items->orWhere('categories.name','like','%'.$request->get('keyword').'%');
        }
        $Items = $Items->paginate(10);
        return view('admin.items.list',compact('Items'));
        
    }

    public function create() {
        $suppliers = Supplier::all();
        return view('admin.items.create',compact('suppliers'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'hpp' => 'required',
            'supplier' => 'required'
        ]); 

        if ($validator->passes()) {

            $Item = new Item();
            $Item->name = $request->name;
            $Item->hpp = $request->hpp;
            $Item->supplier_id = $request->supplier;
            $Item->save();

            $request->session()->flash('success','Item Berhasil Ditambahkan.');

            return response([
                'status' => true,
                'message' => 'Item Berhasil Ditambahkan.'
            ]);

        } else {
            return response([
                'status' => true,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function edit($id, Request $request) {

        $Item = Item::find($id);
        if (empty($Item)) {
            $request->session()->flash('error', 'Data Tidak Ditemukan');
            return redirect()->route('items.index');
        }

        $suppliers = Supplier::all();
        return view('admin.items.edit',compact('Item', 'suppliers'));
    }

    public function update($id, Request $request) {

        $Item = Item::find($id);

        if (empty($Item)) {
            $request->session()->flash('error', 'Data Tidak Ditemukan');
            return response([
                'status' => false,
                'notFound' => true
            ]);
            //return redirect()->route('sub-categories.index');
        }


        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'hpp' => 'required',
            'supplier' => 'required'
        ]); 

        if ($validator->passes()) {

            $Item->name = $request->name;
            $Item->hpp = $request->hpp;
            $Item->supplier_id = $request->supplier;
            $Item->save();

            $request->session()->flash('success','Item Berhasil DiUpdate.');

            return response([
                'status' => true,
                'message' => 'Item Berhasil DiUpdate.'
            ]);

        } else {
            return response([
                'status' => true,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request) {
        $Item = Item::find($id);

        if (empty($Item)) {
            $request->session()->flash('error', 'Data Tidak Ditemukan');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $Item->delete();

        $request->session()->flash('success','Item Berhasil DiHapus.');

            return response([
                'status' => true,
                'message' => 'Item Berhasil DiHapus.'
            ]);
    }
}
