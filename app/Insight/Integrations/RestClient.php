<?php

namespace Insight\Integrations;

use Illuminate\Support\Facades\Log;
use Insight\Portal\Exceptions\WebservicesUnavailableException;
use Guzzle\Service\Client;
use Guzzle\Http\Exception\RequestException;
use Guzzle\Plugin\Oauth\OauthPlugin;

class RestClient
{

    protected $_baseUrl;

    protected $_config;

    protected $client;


    public function __construct($baseUrl = null)
    {
        $this->_baseUrl = $baseUrl;
        $this->client   = new Client($baseUrl);
    }


    /**
     * Sets OAuth subscriber to client
     *
     * @param $consumer_key
     * @param $consumer_secret
     * @param $token
     * @param $token_secret
     */
    public function setOath($consumer_key, $consumer_secret, $token, $token_secret)
    {
        $oauth = new OauthPlugin([
            'consumer_key'    => $consumer_key,
            'consumer_secret' => $consumer_secret,
            'token'           => $token,
            'token_secret'    => $token_secret
        ]);
        $this->client->addSubscriber($oauth);
    }


    /**
     * Get the response as decoded JSON string
     *
     * @param $resource
     * @param $params
     *
     * @return mixed
     * @throws WebservicesUnavailableException
     */
    public function getJSON($resource, $params = [ ])
    {
        $headers  = [ "Content-Type" => "application/json", "Accept" => "application/json" ];
        $response = $this->get($resource, $headers, $params);

        return $response->json();
    }


    /**
     * GET method
     *
     * @param string $resource
     * @param mixed  $headers
     * @param mixed  $params
     *
     * @return GuzzleHttp\Message\ResponseInterface|null
     * @throws WebservicesUnavailableException
     */
    public function get($resource, $headers = [ ], $params = [ ])
    {
        $options['query'] = $params;
        try {
            $request = $this->client->get($resource, $headers, $options);

            return $this->client->send($request);
        } catch (RequestException $e) {
            $this->logErrors($e);
            throw new WebservicesUnavailableException("Webservice not accessible");
        }
    }


    /**
     * @param $e
     */
    protected function logErrors($e)
    {
        Log::error($e->getMessage());
    }


    /**
     * POST method
     *
     * @param string $resource
     * @param mixed  $data
     * @param string $dataType : body | json
     *
     * @return GuzzleHttp\Message\ResponseInterface
     * @throws WebservicesUnavailableException
     */
    public function post($resource, $data, $dataType = 'body')
    {
        $postBody = [ $dataType => $data ];
        try {
            $response = $this->client->post($resource, null, $postBody);

            return $response;
        } catch (RequestException $e) {
            $this->logErrors($e);
            throw new WebservicesUnavailableException("Webservice not accessible");
        }
    }
}
