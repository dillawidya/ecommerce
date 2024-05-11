<?php

namespace App\Http\Controllers\admin;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\ShippingCharge;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function create() {
        $cities = City::get();
        $data['cities'] = $cities;

        $shippingCharges = ShippingCharge::select('shipping_charges.*','cities.name')
                            ->leftJoin('cities','cities.id','shipping_charges.city_id')->get();
        $data['shippingCharges'] = $shippingCharges;
        return view('admin.shipping.create',$data);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(),[
            'city' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()) {

            $count = ShippingCharge::where('city_id',$request->city)->count();

            if ($count > 0) {
                session()->flash('error','Shipping Already Added');
                return response()->json([
                    'status' => true,
                ]);
            }
            
            $shipping = new ShippingCharge();
            $shipping->city_id = $request->city;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success','Shipping Added Successfully');

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

    public function edit($id) {

        $shippingCharge = ShippingCharge::find($id);

        $cities = City::get();
        $data['cities'] = $cities;
        $data['shippingCharge'] = $shippingCharge;

        return view('admin.shipping.edit',$data);
    }

    public function update($id, Request $request) {

        $shipping = ShippingCharge::find($id);

        $validator = Validator::make($request->all(),[
            'city' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()) {

            if ($shipping == null) {
                session()->flash('error','Shipping Not Found');
    
                return response()->json([
                    'status' => true,
                ]);
            }
            
            $shipping->city_id = $request->city;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success','Shipping Updated Successfully');

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

    public function destroy($id) {

        $shippingCharge = ShippingCharge::find($id);

        if ($shippingCharge == null) {
            session()->flash('error','Shipping Not Found');

            return response()->json([
                'status' => true,
            ]);
        }

        $shippingCharge->delete();

        session()->flash('success','Shipping Deleted Successfully');

        return response()->json([
            'status' => true,
        ]);
    }
}
