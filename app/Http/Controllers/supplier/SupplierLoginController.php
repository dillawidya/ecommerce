<?php

namespace App\Http\Controllers\supplier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupplierLoginController extends Controller
{
    public function index() {
        return view('supplier.login');
    }

    public function authenticate(Request $request) {

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            
            if (Auth::guard('supplier')->attempt(['email' => $request->email, 'password' => $request->password],$request->get('remember'))) {

                $supplier = Auth::guard('supplier')->user();

                if ($supplier->role == 3) {
                    return redirect()->route('supplier.dashboard');

                } else {

                    Auth::guard('supplier')->logout();
                    return redirect()->route('supplier.login')->with('error','You are not authorized to access supplier panel');
                }
                

            } else {
                return redirect()->route('supplier.login')->with('error','Either Email/Password is incorrect');
            }

        } else {
            return redirect()->route('supplier.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

    }
}
