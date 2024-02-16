<?php

namespace EnvialoSimple;

use EnvialoSimple\Transaccional\Common\Constants;
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

    public function __construct(string $apikey, ?string $apiurl = null)
    {
        if (is_null($apiurl)) {
            $apiurl = Constants::API_URL;
        }

        $this->apikey = $apikey;
        $this->http = new Http($apikey, $apiurl);
        $this->mail = new Mail($this->http);
    }
}
