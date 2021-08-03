<?php

namespace Hanoivip\Iap\Services;

use Hanoivip\GameContracts\Contracts\IGameOperator;

class GameOrder implements IOrderGenerator
{
    private $operator;
    
    public function __construct(IGameOperator $ops)
    {
        $this->operator = $ops;
    }
    
    public function generate($user, $server, $role, $item)
    {
        return $this->operator->order($user, $server, $item, ['roleid' => $role]);
    }

}

