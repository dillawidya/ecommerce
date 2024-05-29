<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\SupplierProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function reportProfit(Request $request) {

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::tomorrow()->toDateString());
        
        $totalPurchase = Transaksi::whereBetween('created_at', [$startDate, $endDate])->sum('grand_total');
        $totalOrder = Order::whereBetween('created_at', [$startDate, $endDate])->sum('grand_total');

        $profit = $totalOrder - $totalPurchase;

        $purchases = Transaksi::whereBetween('created_at', [$startDate, $endDate])
                          ->select('created_at', 'grand_total')
                          ->get();
        
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
                    ->select('created_at', 'grand_total')
                    ->get();
        
        return view('admin.report.report-profit',[
            'totalPurchase' => $totalPurchase,
            'totalOrder' => $totalOrder,
            'profit' => $profit,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'purchases' => $purchases,
            'orders' => $orders
        ]);

    }

    public function reportPurchase(Request $request) {

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::tomorrow()->toDateString());

   
        $report = SupplierProduct::whereBetween('supplier_products.created_at', [$startDate, $endDate])
                    ->where('supplier_products.qty_beli', '>', 0)
                    ->join('users', 'users.id', '=', 'supplier_products.user_id')
                    ->select(
                        'supplier_products.*',
                        'users.name as supplier_name',
                        DB::raw('harga * qty_beli as total_price')
                    )
                    ->get();
        
        $total = SupplierProduct::whereBetween('supplier_products.created_at', [$startDate, $endDate])
                    ->where('supplier_products.qty_beli', '>', 0)
                    ->join('users', 'users.id', '=', 'supplier_products.user_id')
                    ->select(
                        'supplier_products.*',
                        'users.name as supplier_name',
                        DB::raw('harga * qty_beli as total_price')
                    )
                    ->get()
                    ->sum('total_price');

        return view('admin.report.report-supplierProduct', [
            'report' => $report,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'total' => $total
        ]);
    }
}
