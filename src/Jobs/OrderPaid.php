<?php

namespace Hanoivip\Iap\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use CurlHelper;

class OrderPaid implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private $order;
    
    private $client;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $clients = DB::table('clients')->where('id', $this->order->client_id)->get();
        if ($clients->isEmpty())
        {
            $this->order->callback_result = 1;
            $this->order->save();
            return;
        }
        $client = $clients->first();
        if (empty($client->callback))
        {
            $this->order->callback_result = 2;
            $this->order->save();
            return;
        }
        // Callback
        $params = ['mapping' => $this->order->client_order, 'value' => $this->order->item_price];
        $url = $client->callback . "?" . http_build_query($params);
        $response = CurlHelper::factory($url)->exec();
        Log::debug('>>>>' . $url);
        Log::debug('>>>>' . $response['content']);
        if ($response['status'] != 200 || $response['data'] == false)
        {
            $this->order->callback_result = 3;
            $this->order->save();
            return;
        }
        if ($response['data']['proc_ret'] != 0)
        {
            $this->order->callback_result = 4;
            $this->order->save();
            return;
        }
        $this->order->callback_result = 100;
        $this->order->save();
        return;
    }
}
