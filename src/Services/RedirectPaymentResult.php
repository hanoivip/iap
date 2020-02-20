<?php

namespace Hanoivip\Iap\Services;

class RedirectPaymentResult implements IPaymentResult
{
    private $url;
    
    public function __construct($url)
    {
        $this->url = $url;
    }
    
    public function getValue()
    {
        return $this->url;
    }

    public function getType()
    {
        return 0;
    }

}