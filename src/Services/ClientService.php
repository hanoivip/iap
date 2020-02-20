<?php

namespace Hanoivip\Iap\Services;


use Hanoivip\Iap\Models\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ClientService
{
    /**
     * Get record by code
     * @param string $clientCode
     * @return Client
     */
    public function getRecord($clientCode)
    {
        $clients = Client::where('code', $clientCode)->get();
        if ($clients->isNotEmpty())
            return $clients->first();
    }
    /**
     * 
     * @param Client $client
     * @param string $item
     * @return Collection collection of ClientIap
     */
    public function getIapItems($client, $item = null)
    {
        if (isset($item))
        {
            $items = collect([]);
            if (gettype($item) == "string")
                $items = DB::table('client_iaps')->where('client_id', $client->id)->where('merchant_id', $item)->get();
            if (gettype($item) == "array")
                $items = DB::table('client_iaps')->where('client_id', $client->id)->whereIn('merchant_id', $item)->get();
            return $items;
        }
        else 
        {
            $items = DB::table('client_iaps')->where('client_id', $client->id)->get();
            return $items;
        }
    }
}