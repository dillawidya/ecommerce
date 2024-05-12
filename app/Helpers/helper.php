<?php

use App\Models\City;
use App\Models\Page;
use App\Models\Order;
use App\Models\Product;
use App\Mail\OrderEmail;
use App\Models\Category;
use Illuminate\Support\Facades\Mail;


function getCategories() {
    return Category::orderBy('created_at')
            ->where('showHome','Yes')
            ->get();
}

function getProductImage($productId) {
    return Product::where('id',$productId)->first();
}

function orderEmail($orderId,$userType="customer") {
    $order = Order::where('id',$orderId)->with('items')->first();

    if ($userType == 'customer') {
        $subject = 'Thanks For Your Order';
        $email = $order->email;
    } else {
        $subject = 'You Have Received an Order';
        $email = env('ADMIN_EMAIL');
    }

    $mailData = [
        'subject' => $subject,
        'order'  => $order,
        'userType' => $userType
    ];

    Mail::to($email)->send(new OrderEmail($mailData));
    //dd($order);
}

function getCityInfo($id) {
    return City::where('id',$id)->first();
}

function staticPages() {
    $pages = Page::orderBy('name','ASC')->get();
    return $pages;
}

?>