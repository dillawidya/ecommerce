<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\ManageStock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ManageStockController extends Controller
{
    public function index(Request $request) {
        $manageStocks = ManageStock::latest('id')
                    ->with('user');
        
        if ($request->get('keyword')) {
            $manageStocks->where('name', 'like', '%' . $request->keyword . '%');
        }
                

        $manageStocks = $manageStocks->paginate(10);
        return view('admin.manage_stock.list',compact('manageStocks'));
    }

    public function create() {
        $users = User::where('role',3)->get();

        return view('admin.manage_stock.create',[
            'users' => $users
        ]);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(),[
            'supplier' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required'
        ]);

        if ($validator->passes()) {

            
            $manageStock = new ManageStock();
            $manageStock->user_id = $request->supplier;
            $manageStock->name = $request->name;
            $manageStock->price = $request->price;
            $manageStock->stock = $request->stock;
            $manageStock->save();

            session()->flash('success','Manage Stock Add Sucessfully');

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

        $manageStock = ManageStock::find($id);
        if (empty($manageStock)) {
            $request->session()->flash('error', 'Data Not Found');
            return redirect()->route('manage-stocks.index');
        }

        $users = User::where('role',3)->get();
        return view('admin.manage_stock.edit', [
            'manageStock' => $manageStock,
            'users' => $users
        ]);
    }

    public function update(Request $request, $id) {

        $manageStock = ManageStock::find($id);

        if (empty($manageStock)) {
            $request->session()->flash('error', 'Data Not Found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }


        $validator = Validator::make($request->all(),[
            'stock' => 'required'
        ]);

        if ($validator->passes()) {

            $manageStock->stock = $request->stock;
            $manageStock->save();

            session()->flash('success','Manage Stock Update Sucessfully');

            return response()->json([
                'status' => true,
                'message' => 'Manage Stock Update Sucessfully'
            ]);

        } else {
            return response()->json([
                'status' => true,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request) {
        $manageStock = ManageStock::find($id);

        if (empty($manageStock)) {
            $request->session()->flash('error', 'Data Not Found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $manageStock->delete();

        $request->session()->flash('success','Manage Stock Successfully to Delete');

            return response([
                'status' => true,
                'message' => 'Manage Stock Successfully to Delete'
            ]);
    }
}
