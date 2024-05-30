<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function index(){

        $totalOrders = Order::where('status','!=','cancelled')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role',1)->count();
        $totalSuppliers = User::where('role',3)->count();
        $totalRevenue = Order::where('status','!=','cancelled')->sum('grand_total');

        


        //Delete temp image here
        $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d H:i:s');

        $temImages = TempImage::where('created_at','<=',$dayBeforeToday)->get();

        foreach ($temImages as $temImage) {

            $path = public_path('/temp/'.$temImage->name);

            //delete main image
            if (File::exists($path)) {
                File::delete($path);
            }

            TempImage::where('id',$temImage->id)->delete();
        }

        return view('admin.dashboard',[
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
            'totalSuppliers' => $totalSuppliers
           
        ]);
    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
