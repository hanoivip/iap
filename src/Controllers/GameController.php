<?php

namespace Hanoivip\Iap\Controllers;

use Hanoivip\Iap\Services\ClientService;
use Hanoivip\Iap\Services\IPaymentResult;
use Hanoivip\Iap\Services\PaymentService;
use Hanoivip\Iap\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class GameController extends Controller
{
    private $clientService;
    
    private $purchaseService;
    
    private $paymentService;
    
    public function __construct(
        ClientService $clientService,
        PurchaseService $purchaseService,
        PaymentService $paymentService)
    {
        $this->clientService = $clientService;
        $this->purchaseService = $purchaseService;
        $this->paymentService = $paymentService;
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getItems(Request $request)
    {
        $client = $request->get('client');
        $clientRec = $this->clientService->getRecord($client);
        if (empty($clientRec))
        {
            return view('hanoivip::purchase-result', ['error' => __('hanoivip::purchase.error')]);
        }
        $items = $this->clientService->getIapItems($clientRec);
        if ($request->ajax())
            return ['items' => $items];
        else
            return view('hanoivip::purchase-iap', ['items' => $items, 'client' => $client]);
    }
    
    public function purchase(Request $request)
    {
        $client = $request->get('client');
        $order = $request->get('order');
        $item = $request->get('item');
        $clientRec = $this->clientService->getRecord($client);
        if (empty($clientRec))
        {
            return view('hanoivip::purchase-result', ['error' => __('hanoivip::purchase.error')]);
        }
        if (empty($order))
        {
            return view('hanoivip::purchase-result', ['error' => __('hanoivip::purchase.error')]);
        }
        $purchaseItem = $this->clientService->getIapItems($clientRec, $item);
        if ($purchaseItem->isEmpty())
        {
            return view('hanoivip::purchase-result', ['error' => __('hanoivip::purchase.item.not-available')]);
        }
        return view('hanoivip::purchase', ['item' => $purchaseItem->first(), 'client' => $client, 'order' => $order]);
    }
    
    public function doPurchase(Request $request)
    {
        $client = $request->get('client');
        $cliOrder = $request->get('order');
        //TODO: order already have item info
        $item = $request->get('item');
        $clientRec = $this->clientService->getRecord($client);
        if (empty($clientRec))
        {
            return view('hanoivip::purchase-result', ['error' => __('hanoivip::purchase.error')]);
        }
        $purchaseItem = $this->clientService->getIapItems($clientRec, $item);
        if ($purchaseItem->isEmpty())
        {
            return view('hanoivip::purchase-result', ['error' => __('hanoivip::purchase.item.not-available')]);
        }
        $purchaseItem = $purchaseItem->first();
        $methods = $this->paymentService->allMethods();
        if (count($methods) <= 0)
        {
            return view('hanoivip::purchase-result', ['error' => __('hanoivip::purchase.payment.empty')]);
        }
        if (count($methods) > 1)
        {
            return view('hanoivip::payment-choose', ['methods' => $methods]);
        }
        $order = $this->purchaseService->newOrder($clientRec, $cliOrder, $purchaseItem);
        // Default
        $this->purchaseService->applyPaymentType($order, $methods[0]->getType());
        $this->purchaseService->applyPaymentId($order, $methods[0]->getId());
        try 
        {
            $payResult = $methods[0]->pay($order);
            return $this->renderResult($payResult);
        } 
        catch (Exception $ex) 
        {
            Log::error('Purchase with default payment method exception: ' . $ex->getMessage());
            return view('hanoivip::purchase-result', ['error' => __('hanoivip::purchase.exception')]);
        }
    }
    
    public function paypalDefaultMethod(Request $request)
    {
        
    }
    
    public function purchaseChooseMethod(Request $request)
    {
        
    }
    
    public function purchaseChooseMethodId(Request $request)
    {
        
    }
    
    /**
     * external url, internal url, view name, raw text
     * @param IPaymentResult $result
     */
    public function renderResult($result)
    {
        switch ($result->getType())
        {
            case 0: //external url
                return redirect($result->getValue());
            case 1: //internal url
                return redirect($result->getValue());
            case 2:
                return view($result->getValue());
            default:
                return response($result->getValue());
        }
    }
}