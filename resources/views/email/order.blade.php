<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Email</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">

    @if ($mailData ['userType'] == 'customer')
    <h1>Thanks For Your Order</h1>
    <h2>Your Order Id Is: #{{ $mailData ['order']->id }}</h2>
    @else
    <h1>You Have Received an Order:</h1>
    <h2>Order Id: #{{ $mailData ['order']->id }}</h2>
    @endif

    

    <h2 class="h5 mb-3">Shipping Address</h2>
    <address>
        <strong>{{ $mailData ['order']->first_name.' '.$mailData ['order']->last_name }}</strong><br>
        {{ $mailData ['order']->address }}<br>
        {{ $mailData ['order']->apartment }}, {{ getCityInfo($mailData ['order']->city_id)->name }} {{ $mailData ['order']->zip }}<br>
        Phone: {{ $mailData ['order']->mobile }}<br>
        Email: {{ $mailData ['order']->email }}
    </address>

    <h2>Products</h2>

    <table cellpadding="3" cellspacing="3" border="0" width="700">
        <thead>
            <tr style="background: #CCC;">
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>                                        
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mailData ['order']->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>Rp. {{ number_format($item->price) }}</td>                                        
                <td>{{ $item->qty }}</td>
                <td>Rp. {{ number_format($item->total) }}</td>
            </tr>
            @endforeach
            
            <tr>
                <th colspan="3" align="right">Subtotal:</th>
                <td>Rp. {{ number_format($mailData ['order']->subtotal) }}</td>
            </tr>
            <tr>
                <th colspan="3" align="right">Discount: {{ (!empty($mailData ['order']->coupon_code)) ? '('.$mailData ['order']->coupon_code.')' :'' }}</th>
                <td>Rp. {{ number_format($mailData ['order']->discount) }}</td>
            </tr>
            
            <tr>
                <th colspan="3" align="right">Shipping:</th>
                <td>Rp. {{ number_format($mailData ['order']->shipping) }}</td>
            </tr>
            <tr>
                <th colspan="3" align="right">Grand Total:</th>
                <td>Rp. {{ number_format($mailData ['order']->grand_total) }}</td>
            </tr>
        </tbody>
    </table>
    
</body>
</html>