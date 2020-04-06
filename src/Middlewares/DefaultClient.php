<?php

namespace Hanoivip\Iap\Middlewares;

use Illuminate\Http\Request;
use Closure;

class DefaultClient
{
    public function handle(Request $request, Closure $next)
    {
        $standalone = config('iap.standalone', true);
        if (!$standalone)
        {
            $default = config('iap.default_client', '');
            if (empty($default))
            {
                return response('Invalid client param', 500);
            }
            else
            {
                $client = $request->get('client');
                if (empty($client))
                    $request->attributes->add(['client' => $default]);
                return $next($request);
            }
        }
        return $next($request);
    }
}
