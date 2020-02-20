<?php

namespace Hanoivip\Iap\Controllers;

use Illuminate\Http\Request;
use Hanoivip\Iap\Services\PaymentService;
use Hanoivip\Iap\Services\PurchaseService;

class PaypalController extends Controller
{
    private $purchase;
    
    private $payment;
    
    public function __construct(
        PurchaseService $purchase,
        PaymentService $payment)
    {
        $this->purchase = $purchase;
        $this->payment = $payment;
    }
    
    public function return(Request $request)
    {
        $order = $request->get('order');
        $paymentType = $request->get('type');
        $paymentId = $request->get('id');
        $ppPayerId = $request->get('PayerID');
        $method = $this->payment->getMethod($paymentType, $paymentId);
        if (empty($method))
        {
            return view('hanoivip::purchase-result', ['error' => __('hanoivip::purchase.error')]);
        }
        if ($method->callback([$order, $ppPayerId]))
        {
            $this->purchase->paid($order);
            return view('hanoivip::purchase-result', ['message' => __('hanoivip::purchase.success')]);
        }
        else 
        {
            return view('hanoivip::purchase-result', ['message' => __('hanoivip::purchase.fail')]);
        }
    }

    public function cancel(Request $request)
    {
        return view('hanoivip::purchase-result', ['error' => __('hanoivip::purchase.cancel')]);
    }
}