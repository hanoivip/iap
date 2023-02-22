<?php

namespace Hanoivip\Iap\Services;

use Hanoivip\Iap\Models\Order;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;

class IapService
{
    private $generator;
    
    private $clients;
    
    public function __construct(
        IOrderGenerator $generator,
        ClientService $clients)
    {
        $this->generator = $generator;
        $this->clients = $clients;
    }
    /**
     * 
     * @param string $client
     * @return array ClientIap to array
     */
    public function items($client)
    {
        if (!isset($client) || empty($client))
        {
            $client = config('iap.default_client', '');
        }
        $items = $this->clients->getIapItems($this->clients->getRecord($client));
        return $items->toArray();
    }
    /**
     * 
     * @param Authenticatable $user
     * @param string $server
     * @param string $role
     * @param string $item
     * @return string
     */
    public function order($user, $server, $role, $item, $client)
    {
        $order = $this->generator->generate($user, $server, $role, $item);
        if (!isset($client) || empty($client))
            $client = config('iap.default_client', '');
        $clientRec = $this->clients->getRecord($client);
        $itemRec = $this->clients->getIapItems($clientRec, $item);
        //Log::debug("Create new order " . $order . '@' . print_r($itemRec, true));
        $log = new Order();
        $log->order = $order;
        $log->client_id = $clientRec->id;
        $log->item = $item;
        $log->item_price = $itemRec->price;
        $log->item_currency = $itemRec->currency;
        $log->user = $user->getAuthIdentifier();
        $log->server = $server;
        $log->role = $role;
        $log->item = $item;
        $log->save();
        return $order;
    }
    
    public function detail($order)
    {
        $log = Order::where('order', $order)->first();
        return $log->toArray();
    }
}