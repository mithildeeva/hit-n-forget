<?php

namespace HitNForget\Requests;


use HitNForget\Exceptions\RequestFailureException;
use HitNForget\Response;
use HitNForget\Services\SocketRequestService;

class SocketRequest
{
    const SOCKET_CONNECTION_INIT_TIMEOUT = 30; # seconds

    private $url;
    private $method;
    private $data;
    private $headers;

    public function __construct(array $url, string $method, string $data, string $headers)
    {
        $this->url = $url;
        $this->method = $method;
        $this->data = $data;
        $this->headers = $headers;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public static function call(Request $request) {
        return SocketRequestService::generateRequest($request)
            ->hit();
    }

    /**
     * @return Response
     * @throws RequestFailureException
     */
    public function hit() {
        $url = $this->url;
        if (! $fp = fsockopen("{$url['ssl']}{$url['host']}", $url['port'], $errNo, $errStr, self::SOCKET_CONNECTION_INIT_TIMEOUT)) {
            fclose($fp);
            throw new RequestFailureException("Failed to Open Socket with error ($errNo) : $errStr");
        }

        fwrite($fp, "$this");
        fclose($fp);

        return new Response($this);
    }

    public function __toString()
    {
        $host = $this->url['host'];
        $path = $this->url['path'] ?? "";
        $query = $this->url['query'] ?? "";

        return "$this->method $path?$query HTTP/1.1\r\n"
            . "HOST: $host\r\n"
            . "$this->headers"
            . "\r\n"
            . $this->data;
    }
}