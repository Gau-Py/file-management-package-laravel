<?php

namespace GoApptiv\FileManagement\Traits;

use GuzzleHttp\Client;
use GoApptiv\FileManagement\Models\Request;

trait RestCall
{
    /**
     * Send a request to any external service
     *
     * @param string $method
     * @param array $data
     * @param string $endpoint
     * @param array $headers
     * @param array $queryParams
     *
     * @return mixed
     */
    public function makeRequest(
        string $method,
        array $data,
        string $endPoint,
        array $headers,
        array $queryParams
    ) {
        $request = $this->buildRequest($method, $data, $endPoint, $headers, $queryParams);

        $client = $this->getClient();
        $options = [
            'headers' => $request->getHeaders(),
            'query' => $request->getQueryParams(),
        ];

        if (!empty($request->getPayload())) {
            $options['json'] = $request->getPayload();
        }

        $response = $client->request($request->getMethod(), $request->getEndPoint(), $options);
        $response = json_decode($response->getBody(), true);
        return $response;
    }

    /**
     * Build Request
     *
     * @param string $method
     * @param string $endPoint
     * @param array $data
     * @param array $headers
     * @param array $queryParams
     *
     * @return Request
     */
    public function buildRequest(
        string $method,
        array $data,
        string $endPoint,
        array $headers,
        array $queryParams
    ) {
        $request = new Request();
        $request->setMethod($method);
        $request->setEndPoint($endPoint);
        $request->setPayload($data);
        $request->setHeaders($headers);
        $request->setQueryParams($queryParams);

        return $request;
    }

    /**
     * Build Client
     */
    private function getClient()
    {
        $client = new Client([
            'timeout' => 20, // Response timeout
            'connect_timeout' => 20, // Connection timeout
        ]);

        return $client;
    }
}
