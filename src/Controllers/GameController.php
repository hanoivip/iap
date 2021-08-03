<?php

namespace Hanoivip\Iap\Controllers;

use Hanoivip\Iap\Models\ClientIap;
use Hanoivip\Iap\Models\Order;
use Hanoivip\Iap\Services\ClientService;
use Hanoivip\Iap\Services\IOrderGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Hanoivip\PaymentContract\Facades\PaymentFacade;

class GameController extends Controller
{
    private $clientService;
    
    private $orderService;
    
    public function __construct(
        ClientService $clientService,
        IOrderGenerator $orderService)
    {
        $this->clientService = $clientService;
        $this->orderService = $orderService;
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
            return view('hanoivip::purchase-result', ['error' => __('hanoivip::iap.error')]);
        }
        $items = $this->clientService->getIapItems($clientRec);
        if ($request->ajax())
            return ['items' => $items];
        else
            return view('hanoivip::purchase-iap', ['items' => $items, 'client' => $client]);
    }
    
    // TODO: make guard: ajax
    public function newOrder(Request $request)
    {
        $client = $request->input('client');//auto
        $svname=$request->input('svname');
        $role=$request->input('role');
        $item=$request->input('item');
        try
        {
            $order = $this->ops->order(Auth::user(), $svname, $item, $role);
            if (empty($order))
            {
                Log::error("Request new order error!");
                return ['error' => 1, 'message' => __('iap.order.error'), 'data' =>[]];
            }
            $purchaseItem=ClientIap::where('merchant_id', $item)->first();
            $orderRec = new Order();
            $orderRec->client = $client;
            $orderRec->user = Auth::user()->getAuthIdentifier();
            $orderRec->server = $svname;
            $orderRec->role = $role;
            $orderRec->order = $order;
            $orderRec->item = $item;
            $orderRec->item_price = $purchaseItem->price;//TODO: get CURRENT price?
            $orderRec->save();
            return ['error' => 0, 'message' => __('iap.order.success'),
                'data' =>[['item' => $item, 'order' => $order]]];
        }
        catch (Exception $ex)
        {
            Log::error("Order make new order exception: " . $ex->getMessage());
            return ['error' => 2, 'message' => $ex->getMessage(), 'data' =>[]];
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