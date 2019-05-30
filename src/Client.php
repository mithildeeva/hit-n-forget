<?php

namespace HitNForget;


use HitNForget\Requests\Request;
use HitNForget\Requests\SocketRequest;

class Client
{

    /**
     * @param Request $request
     * @param string $requestType
     * @return Response
     */
    public static function call(Request $request, $requestType = SocketRequest::class)
    {
        return call_user_func([$requestType, 'call'], $request);
    }
}