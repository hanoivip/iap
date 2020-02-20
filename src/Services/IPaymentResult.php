<?php

namespace Hanoivip\Iap\Services;

interface IPaymentResult
{
    public function getType();
    
    public function getValue();
}