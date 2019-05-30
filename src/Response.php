<?php

namespace HitNForget;


class Response
{
    private $generatedRequest;

    public function __construct($generatedRequest)
    {
        $this->generatedRequest = $generatedRequest;
    }

    public function getRequestGenerated()
    {
        return $this->generatedRequest;
    }
}