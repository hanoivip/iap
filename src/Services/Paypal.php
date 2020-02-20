<?php

namespace Hanoivip\Iap\Services;

use Hanoivip\Iap\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Exception;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class Paypal extends AbsPayment
{   
    private function getApiContext()
    {
        Log::debug(print_r($this->config, true));
        if (!empty($this->config))
        {
            $apiContext = new ApiContext(new OAuthTokenCredential($this->config['client_id'], $this->config['secret']));
            $apiContext->setConfig($this->config['settings']);
            return $apiContext;
        }
    }
    
    /**
     * 
     * @param Order $order
     * @return IPaymentResult
     */
    public function pay(Order $order)
    {
        if (empty($order->payment_type) || empty($order->payment_id))
            throw new Exception("Payment type and id must set");
        // 1. Prepare Paypal object
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName('Prepaid Code: ' . $order->order)
        ->setCurrency('USD')
        ->setQuantity(1)
        ->setPrice($order->item_price);
        // 2.
        $item_list = new ItemList();
        $item_list->setItems(array(
            $item_1
        ));
        // 3. 
        $amount = new Amount();
        $amount->setCurrency('USD')->setTotal($order->item_price);
        // 4. 
        $transaction = new Transaction();
        $transaction->setAmount($amount)
        ->setItemList($item_list)
        ->setDescription('Buy webcoin')
        ->setCustom($order->order);
        // 5.
        $params = ['order' => $order->order, 'type' => $this->getType(), 'id' => $this->getId()];
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('paypal.return', $params))
        ->setCancelUrl(URL::route('paypal.cancel', $params));
        // 6.
        $payment = new Payment();
        $payment->setIntent('Sale')
        ->setPayer($payer)
        ->setRedirectUrls($redirect_urls)
        ->setTransactions(array($transaction));
        
        $apiContext = $this->getApiContext();
        if (empty($apiContext))
        {
            throw new Exception("Paypal configuration not found");
        }
        $payment->create($apiContext);
        Cache::put('PaypalPaymentID' . $order->order, $payment->getId(), now()->addMinutes(5));
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                return new RedirectPaymentResult($link->getHref());
            }
        }
        
        throw new Exception("Paypal flow is not approved");
    }
    
    
    public function getType()
    {
        return "paypal";
    }

    public function getId()
    {
        return $this->config['id'];
    }
    
    public function callback($params)
    {
        $order = $params[0];
        $payerId = $params[1];
        $paymentId = Cache::get('PaypalPaymentID' . $order);
        if (empty($paymentId))
            throw new Exception("Paypal payment id empty");
        $apiContext = $this->getApiContext();
        $payment = Payment::get($paymentId, $apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        /**Execute the payment **/
        $result = $payment->execute($execution, $apiContext);
        if ($result->getState() == 'approved') {
            return true;
        }
        return false;
    }


}