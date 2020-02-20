<?php

namespace Hanoivip\Iap\Services;

use Hanoivip\Iap\Models\Order;

interface IPayment
{
    /**
     * 
     * @param Order $order
     * @return IPaymentResult
     */
    function pay(Order $order);
    
    function getType();
    
    function getId();
    
    function callback($params);
}