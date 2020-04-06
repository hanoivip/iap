<?php

namespace Hanoivip\Iap\Controllers;

use Hanoivip\Iap\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{   
    public function query(Request $request)
    {
        $cliOrder = $request->get('mapping');
        $orders = Order::where('client_order', $cliOrder)->get();
        if ($orders->isNotEmpty())
        {
            $order = $orders->first();
            return ['value' => $order->item_price, 'paid' => ($order->payment_result == 1)];
        }
        return ['value' => 0, 'paid' => false];
    }

}