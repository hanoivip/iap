<?php

namespace Hanoivip\Iap\Services;

use Hanoivip\Iap\Models\Client;
use Hanoivip\Iap\Models\Order;
use Illuminate\Support\Facades\DB;
use Hanoivip\Iap\Jobs\OrderPaid;
use Hanoivip\Iap\Models\ClientIap;

class PurchaseService
{
    public function generateOrderId()
    {
        $order = '';
        for ($i = 0; $i<10; $i++)
        {
            $order .= mt_rand(0,9);
        }
        return 'WEB' . $order;   
    }
    
    /**
     * 
     * @param Client $client
     * @param string $cliOrder
     * @param ClientIap $item
     * @return Order
     */
    public function newOrder($client, $cliOrder, $item)
    {
        $order = new Order();
        $order->order = $this->generateOrderId();
        $order->client_id = $client->id;
        $order->client_order = $cliOrder;
        $order->item = $item->merchant_id;
        $order->item_price = $item->price;
        $order->save();
        
        return $order;
    }
    
    /**
     * 
     * @param Order $order
     * @param string $type
     */
    public function applyPaymentType($order, $type)
    {
        $order->payment_type = $type;
        $order->save();
    }
    
    /**
     * 
     * @param Order $order
     * @param string $id
     */
    public function applyPaymentId($order, $id)
    {
        $order->payment_id = $id;
        $order->save();
    }
    
    /**
     * 
     * @param string $serial
     * @return Order
     */
    public function getOrder($serial)
    {
        $orders = Order::where('order', $serial)->get();
        if ($orders->isNotEmpty())
            return $orders->first();
    }
    
    /**
     * 
     * @param string $order
     */
    public function paid($order)
    {
        $orders = Order::where('order', $order)->get();
        if ($orders->isNotEmpty())
        {
            $orderRec = $orders->first();
            // save result
            $orderRec->payment_result = 1;
            $orderRec->save();
            // notify client
            dispatch(new OrderPaid($orderRec));
        }
    }
    
    /**
     * 
     * @param string $order
     */
    public function cancel($order)
    {
        //$orders = DB::table('orders')->where('order', $order)->get(); => return collection of stdClass
        $orders = Order::where('order', $order)->get();// return collection of eloquent object
        if ($orders->isNotEmpty())
        {
            $orderRec = $orders->first();
            // save result
            $orderRec->payment_result = 2;
            $orderRec->save();
        }
    }
}