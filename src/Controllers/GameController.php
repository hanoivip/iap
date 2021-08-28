<?php

namespace Hanoivip\Iap\Controllers;

use Hanoivip\Iap\Services\ClientService;
use Hanoivip\Iap\Services\IapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class GameController extends Controller
{
    private $clientService;
    
    private $iapService;
    
    public function __construct(
        ClientService $clientService,
        IapService $iapService)
    {
        $this->clientService = $clientService;
        $this->iapService = $iapService;
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getItems(Request $request)
    {
        $client = $request->input('client');
        $clientRec = $this->clientService->getRecord($client);
        if (empty($clientRec))
        {
            if ($request->ajax())
            {
                return ['error' => 1, 'message' => 'invalid client', 'data' => []];
            }
            else
            {
                return view('hanoivip::purchase-result', ['error' => __('hanoivip::iap.error')]);
            }
        }
        $items = $this->clientService->getIapItems($clientRec);
        if ($request->ajax())
        {
            return ['error' => 0, 'message' => 'success', 'data' => ['items' => $items]];
        }
        else
        {
            return view('hanoivip::purchase-iap', ['items' => $items, 'client' => $client]);
        }
    }
    
    public function newOrder(Request $request)
    {
        $client = $request->input('client');
        $svname=$request->input('svname');
        $role=$request->input('role');
        $item=$request->input('item');
        try
        {
            $order = $this->iapService->order(Auth::user(), $svname, $role, $item, $client);
            return ['error' => 0, 'message' => __('hanoivip::order.success'),
                'data' =>['item' => $item, 'order' => $order]];
        }
        catch (Exception $ex)
        {
            Log::error("Order make new order exception: " . $ex->getMessage());
            return ['error' => 1, 'message' => __("hanoivip::order.failure"), 'data' =>[]];
        }
    }
    
    public function askConfirm(Request $request)
    {
        $client = $request->input('client');
        $order = $request->input('order');
        $item = $request->input('item');
        $clientRec = $this->clientService->getRecord($client);
        if (empty($clientRec))
        {
            return view('hanoivip::purchase-result', ['error' => __('hanoivip::iap.error')]);
        }
        if (empty($order))
        {
            return view('hanoivip::purchase-result', ['error' => __('hanoivip::iap.error')]);
        }
        $purchaseItem = $this->clientService->getIapItems($clientRec, $item);
        if ($purchaseItem->isEmpty())
        {
            return view('hanoivip::purchase-result', ['error' => __('hanoivip::iap.item.not-available')]);
        }
        return view('hanoivip::purchase', ['item' => $purchaseItem->first(), 'client' => $client, 'order' => $order]);
    }
    
}