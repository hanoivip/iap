<?php

namespace Hanoivip\Iap\Services;

class SelfOrder implements IOrderGenerator
{
    public function generate($user, $server, $role, $item)
    {
        $order = '';
        for ($i = 0; $i<10; $i++)
        {
            $order .= mt_rand(0,9);
        }
        return 'WEB' . $order;
    }

}

