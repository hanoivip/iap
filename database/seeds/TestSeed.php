<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestSeed extends Seeder
{
    public function run()
    {
        DB::table('clients')->insert([
            'code' => 'test',
            'description' => 'Test Client',
            'callback' => 'http://purchase.test/test/callback',
        ]);
        $client = DB::table('clients')->get()->first();
        DB::table('client_iaps')->insert([
            [
                'client_id' => $client->id,
                'merchant_id' => 'merchant_id1',
                'merchant_title' => 'Test of merchant 1',
                'merchant_image' => 'Image of merchant 1',
                'price' => '1'
            ],
            [
                'client_id' => $client->id,
                'merchant_id' => 'merchant_id2',
                'merchant_title' => 'Test of merchant 2',
                'merchant_image' => 'Image of merchant 2',
                'price' => '3'
            ],
        ]);
    }
}
