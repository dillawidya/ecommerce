<?php

namespace App\Http\Controllers\supplier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeSupplierController extends Controller
{
    public function index() {
        
        $supplier = Auth::guard('supplier')->user();
        $user = Auth::user();
        $supplierProducts = $user->supplierProducts;
        $supplierProductCount = $supplierProducts->count();

        $totalPendapatan = $supplierProducts->sum(function($product) {
            return $product->qty_beli * $product->harga;
        });

        return view('supplier.dashboard', [
            'supplier' => $supplier,
            'supplierProductCount' => $supplierProductCount,
            'totalPendapatan' => $totalPendapatan
        ]);

        // echo 'Welcome '.$supplier->name.' <a href="'.route('supplier.logout').'">Logout</a>'; 
    } 

    public function logout() {
        Auth::guard('supplier')->logout();
        return redirect()->route('supplier.login');
    }

    
}
