<?php

namespace Hanoivip\Iap\Services;

use Hanoivip\GameContracts\Contracts\IGameOperator;
use Hanoivip\Game\Facades\ServerFacade;

class GameOrder implements IOrderGenerator
{
    private $operator;
    
    public function __construct(IGameOperator $ops)
    {
        $this->operator = $ops;
    }
    
    public function generate($user, $svname, $role, $item)
    {
        //TODO: need server provider?
        $server = ServerFacade::getServerByName($svname);
        return $this->operator->order($user, $server, $item, ['roleid' => $role]);
    }

}

