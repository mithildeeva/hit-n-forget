<?php

namespace HitNForget\Requests;


class Request
{
    private $url;
    private $method;
    private $data;
    private $headers;

    public function __construct(string $url, string $method, $data = null, array $headers = [])
    {
        $this->setUrl($url);
        $this->setMethod($method);
        $this->setData($data);
        $this->setHeaders($headers);
    }

    /**
     * @param mixed $url
     * @return Request
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param mixed $method
     * @return Request
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param mixed $data
     * @return Request
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param array $headers
     * converts all http header names to small case for easy lookup
     * @return Request
     */
    public function setHeaders(array $headers)
    {
        $smallCaseHeaders = [];
        foreach ($headers as $header => $values) {
            $smallCaseHeaders[strtolower($header)] = $values;
        }
        $this->headers = $smallCaseHeaders;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

}