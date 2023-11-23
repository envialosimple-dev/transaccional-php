<?php

namespace EnvialoSimple\Transaccional\Endpoints;

use EnvialoSimple\Transaccional\Common\Constants;
use EnvialoSimple\Transaccional\Common\Http;
use EnvialoSimple\Transaccional\Exceptions\ESTRException;
use EnvialoSimple\Transaccional\Exceptions\ESTRForbiddenException;
use EnvialoSimple\Transaccional\Exceptions\ESTRHourlyLimitReachedException;
use EnvialoSimple\Transaccional\Helpers\Builder\MailParams;

class Mail
{
    public const ENDPOINT_MAIL_SEND = '/mail/send';

    private Http $http;

    public function __construct(Http $http)
    {
        $this->http = $http;
    }

    public function send(MailParams $params)
    {
        $url = Constants::API_URL . Mail::ENDPOINT_MAIL_SEND;

        list($httpCode, $body) = $this->http->post($url, $params->toArray());

        if ($httpCode >= 400) {
            switch ($httpCode) {
                case 429:
                    throw new ESTRHourlyLimitReachedException();
                    break;
                case 403:
                    throw new ESTRForbiddenException('Make sure API Key is correct and not disabled');
                    break;
                default:
                    throw new ESTRException(sprintf('The server responded with code %s', $httpCode));
            }
        }

        return $body;
    }
}
