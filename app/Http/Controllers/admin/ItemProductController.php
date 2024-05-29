<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\ItemProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ItemProductController extends Controller
{
    public function index(Request $request) {
        $itemProductQuery = ItemProduct::latest('id')
                    ->with('user');
        
        if ($request->get('keyword')) {
            $itemProductQuery->where('name', 'like', '%' . $request->keyword . '%');
        }
                

        $itemProducts = $itemProductQuery->paginate(10);

        return view('admin.items.list', compact('itemProducts'));
    }

    public function create() {

        $users = User::where('role',3)->get();

        return view('admin.items.create', compact('users'));
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'hpp' => 'required|numeric',
            'supplier' => 'required'
        ]);

        if ($validator->passes()) {

            
            $itemProduct = new ItemProduct();
            $itemProduct->name = $request->name;
            $itemProduct->hpp = $request->hpp;
            $itemProduct->user_id = $request->supplier;
            $itemProduct->save();

            session()->flash('success','Item Add Sucessfully');

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

        $itemProduct = ItemProduct::find($id);
        if (empty($itemProduct)) {
            $request->session()->flash('error', 'Data Not Found');
            return redirect()->route('item-products.index');
        }

        $users = User::where('role',3)->get();
        return view('admin.items.edit', [
            'itemProduct' => $itemProduct,
            'users' => $users
        ]);
    }

    public function update(Request $request, $id) {

        $itemProduct = ItemProduct::find($id);

        if (empty($itemProduct)) {
            $request->session()->flash('error', 'Data Not Found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
            //return redirect()->route('sub-categories.index');
        }


        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'hpp' => 'required|numeric',
            'supplier' => 'required'
        ]);

        if ($validator->passes()) {

            $itemProduct->name = $request->name;
            $itemProduct->hpp = $request->hpp;
            $itemProduct->user_id = $request->supplier;
            $itemProduct->save();

            session()->flash('success','Item Update Sucessfully');

            return response()->json([
                'status' => true,
                'message' => 'Item Update Sucessfully'
            ]);

        } else {
            return response()->json([
                'status' => true,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request) {
        $itemProduct = ItemProduct::find($id);

        if (empty($itemProduct)) {
            $request->session()->flash('error', 'Data Not Found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $itemProduct->delete();

        $request->session()->flash('success','Item Successfully to Delete');

            return response([
                'status' => true,
                'message' => 'Item Successfully to Delete'
            ]);
    }
}
