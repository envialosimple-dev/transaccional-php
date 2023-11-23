<?php

namespace EnvialoSimple;

use EnvialoSimple\Transaccional\Endpoints\Mail;
use EnvialoSimple\Transaccional\Common\Http;

/**
 * PHP SDK for EnvíaloSimple Transaccional
 *
 * Class Transaccional
 * @package Transaccional
 */
class Transaccional
{
    protected string $apikey;

    protected Http $http;

    public Mail $mail;

    public function __construct(string $apikey)
    {
        $this->apikey = $apikey;
        $this->http = new Http($apikey);
        $this->mail = new Mail($this->http);
    }
}
