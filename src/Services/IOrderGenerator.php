<?php

namespace Hanoivip\Iap\Services;

use Hanoivip\GameContracts\ViewObjects\UserVO;

interface IOrderGenerator
{
    /**
     * 
     * @param UserVO $user
     * @param string $server
     * @param string $role
     * @param string $item
     * @return string
     */
    public function generate($user, $server, $role, $item);
}

