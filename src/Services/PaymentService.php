<?php

namespace Hanoivip\Iap\Services;


class PaymentService
{
    /**
     * @return IPayment[]
     */
    public function allMethods($typeFilter = null, $idFilter = null)
    {
        $all = [];
        $methods = config('purchase', []);
        foreach ($methods as $type => $detail)
        {
            if (isset($typeFilter) && $type != $typeFilter)
                continue;
            $clazz = $detail['clazz'];
            $id = $detail['id'];
            if (isset($idFilter) && $id != $idFilter)
                continue;
            $config = $this->getDetailConfig($type, $id);
            $instance = app()->make($clazz, ['config' => $config]);
            if (!empty($instance))
                $all[] = $instance;
        }
        return $all;
    }
    
    public function getDetailConfig($type, $id)
    {
        $ids = config($type, []);
        if (isset($ids[$id]))
            return $ids[$id];
    }
    
    public function getMethod($type, $id)
    {
        $all = $this->allMethods($type, $id);
        if (count($all) >= 1)
            return $all[0];
    }
}