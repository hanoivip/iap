<?php

namespace Hanoivip\Iap\Services;


abstract class AbsPayment implements IPayment
{
    protected $config;
    
    public function __construct($config)
    {
        $this->config = $config;
    }
}