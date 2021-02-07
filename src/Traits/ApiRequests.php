<?php

namespace Frontier\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

trait ApiRequests
{
    private $config;
    private $base_url;
    private $client;
    private $cookie_jar;
    private $headers = [
        'Content-Type' => 'application/x-www-form-urlencoded',
        'Accept-Encoding' => 'deflate, gzip'
    ];

    function __construct(array $config = [])
    {
        if (empty($config)) {
            throw new \Exception('Frontier router configuration cannot be blank');
        }
        if (empty($config['ip'])) {
            throw new \Exception('Frontier router ip is missing');
        }
        if (empty($config['password'])) {
            throw new \Exception('Frontier router password is missing');
        }

        $this->base_url = "http://$config[ip]";
        $this->config = $config;

        $this->client = new Client([
            'base_uri' => $this->base_url
        ]);
        $this->cookie_jar = new CookieJar();
    }

    function setHeaders($headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    function apiRequestor($method, $path, $payload = [])
    {
        $data = [
            'headers' => $this->headers,
            'cookies' => $this->cookie_jar,
            'http_errors' => false,
        ];

        if (!empty($payload)) {
            $data['json'] = $payload;
        }

        // Make the request
        $response = $this->client->request($method, $path, $data);

        // Update the xsrf token for the next request
        $xsrf = $this->cookie_jar->getCookieByName('XSRF-TOKEN');
        if ($xsrf) {
            $this->setHeader('X-XSRF-TOKEN', $this->cookie_jar->getCookieByName('XSRF-TOKEN')->getValue());
        }

        return $this->apiResponse($response);
    }

    private function apiResponse(\Psr\Http\Message\ResponseInterface $response)
    {
        return new class($response) {
            public $json;

            public function __construct(\Psr\Http\Message\ResponseInterface $response)
            {
                $this->response = $response;
                $this->json = json_decode($response->getBody()->getContents(), true);
            }

            public function assertStatus($statusCode = 200)
            {
                if ($this->response->getStatusCode() !== $statusCode) {
                    throw new \Exception("Invalid status code: {$this->response->getStatusCode()}, expected $statusCode");
                }

                return $this;
            }

            public function statusCode()
            {
                return $this->response->getStatusCode();
            }

            public function json($key = '')
            {
                if ($key) {
                    return $this->json[$key];
                }

                return $this->json;
            }
        };
    }

    function get($path, $payload = [])
    {
        return $this->apiRequestor('get', $path, $payload);
    }

    function post($path, $payload = [])
    {
        return $this->apiRequestor('post', $path, $payload);
    }
}
