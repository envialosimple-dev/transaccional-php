<?php

namespace EnvialoSimple\Transaccional\Common;

use EnvialoSimple\Transaccional\Exceptions\ESTRHttpException;

class Http
{
    protected string $apikey;

    protected string $apiurl;

    public function __construct(string $apikey, string $apiurl)
    {
        $this->apikey = $apikey;
        $this->apiurl = $apiurl;
    }

    public function post(string $url, array $data): array
    {
        // Convert the data to JSON format
        $jsonData = json_encode($data);

        // Initialize cURL session
        $ch = curl_init($this->apiurl . $url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apikey
        ));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new ESTRHttpException("cURL Error: " . curl_error($ch));
        }

        curl_close($ch);

        if ($response === false) {
            throw new ESTRHttpException("Invalid response");
        }

        $responseData = json_decode($response, true);

        if (!is_array($responseData)) {
            throw new ESTRHttpException("Invalid JSON");
        }

        if ($httpCode === 500) {
            throw new ESTRHttpException("Server error");
        }

        return [$httpCode, $responseData];
    }
}
