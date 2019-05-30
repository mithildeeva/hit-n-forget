<?php

namespace HitNForget\Services;

use HitNForget\Exceptions\InvalidUrlException;
use HitNForget\Requests\Request;
use HitNForget\Requests\SocketRequest;

class SocketRequestService
{
    const HEADER_CONNECTION = 'connection';
    const HEADER_CONTENT_TYPE = 'content-type';

    private function __construct() {}

    /**
     * @param Request $request
     * @return SocketRequest
     */
    public static function generateRequest(Request $request) {
        $method = self::getMethod($request->getMethod());
        $data = self::getData($request->getData(), $method);
        $url = self::getParsedUrl($request);
        $headers = self::getHeaders($request->getHeaders(), $request->getData(), $method);
        return new SocketRequest(
            $url,
            $method,
            $data,
            $headers
        );
    }

    private static function getParsedUrl(Request $request) {
        if (! filter_var($request->getUrl(), FILTER_VALIDATE_URL)) throw new InvalidUrlException();
        $parsedUrl = parse_url($request->getUrl());

        $parsedUrl['port'] = $parsedUrl['port'] ?? 80;
        # to support https
        $parsedUrl['ssl'] = isset($parsedUrl['scheme']) && $parsedUrl['scheme'] === 'https' ? 'ssl://' : '';

        if (! ('GET' === self::getMethod($request->getMethod()) && ! empty($request->getData()))) {
            return $parsedUrl;
        }
        $queryParams = self::getQueryParams($request->getData());

        $parsedUrl['query'] =
            isset($parsedUrl['query']) ? "{$parsedUrl['query']}&$queryParams" : $queryParams;
        return $parsedUrl;
    }

    private static function getMethod(string $method) {
        $method = $method ?? "GET";
        return strtoupper($method);
    }

    private static function getData($data, $method) {
        if (is_null($data) || "GET" === self::getMethod($method)) return "";
        if (is_array($data)) return json_encode($data);

        return $data;
    }

    private static function getHeaders($headers, $data, $method) {
        if (empty($headers)) $headers = [];

        if (! isset($headers[self::HEADER_CONNECTION])) $headers[self::HEADER_CONNECTION] = 'Close';

        $parsedData = self::getData($data, $method);

        if (! isset($headers[self::HEADER_CONTENT_TYPE]) && ! empty($parsedData))
            $headers[self::HEADER_CONTENT_TYPE] = is_array($data) ? 'application/json' : 'text/plain;charset=UTF-8';

        $headerString = empty($parsedData) ? "" : "Content-Length: " . strlen($parsedData) . "\r\n";
        foreach ($headers as $header => $values) {
            $headerString = "$headerString$header: $values\r\n";
        }

        return $headerString;
    }

    private static function getQueryParams($data) {
        if (is_string($data)) return $data;

        return http_build_query($data);
    }
}