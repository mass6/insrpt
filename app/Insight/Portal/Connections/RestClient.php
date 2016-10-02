<?php
namespace Insight\Portal\Connections;

use Illuminate\Support\Facades\Log;
use Insight\Portal\Exceptions\WebservicesUnavailableException;
use Guzzle\Service\Client;
use Guzzle\Http\Exception\RequestException;
use Guzzle\Plugin\Oauth\OauthPlugin;

class RestClient
{
    protected $_baseUrl;
    protected $_config;
    protected $_client;

    public function __construct($baseUrl = '', $config = array())
    {
        $this->_baseUrl = $baseUrl;
        $this->_config = $config;
        $this->_setDefaultConfig();
        $this->_client = new Client($this->_baseUrl);
        $oauth = new OauthPlugin($this->_config);
        $this->_client->addSubscriber($oauth);
    }

    private function _setDefaultConfig()
    {
        if (!$this->_baseUrl)
            $this->_baseUrl = getenv('REST_BASE_URL');
        if (!isset($this->_config['consumer_key']))
            $this->_config['consumer_key'] = getenv('REST_CONSUMER_KEY');
        if (!isset($this->_config['consumer_secret']))
            $this->_config['consumer_secret'] = getenv('REST_CONSUMER_SECRET');
        if (!isset($this->_config['token']))
            $this->_config['token'] = getenv('REST_TOKEN');
        if (!isset($this->_config['token_secret']))
            $this->_config['token_secret'] = getenv('REST_TOKEN_SECRET');
    }

    /**
     * GET method
     * @param string $resource
     * @param mixed $headers
     * @param mixed $params
     * @return GuzzleHttp\Message\ResponseInterface|null
     */
    public function get($resource, $headers = array(), $params = array())
    {
        $options['query'] = $params;
        try {
            $request = $this->_client->get($resource, $headers, $options);
            return $this->_client->send($request);
        } catch (RequestException $e) {
            $this->logErrors($e);
            throw new WebservicesUnavailableException("Webservice not accessible");
        }
    }

    /**
     * get the response as decoded JSON string
     * @param $resource
     * @param $params
     * @return mixed
     * @throws WebservicesUnavailableException
     */
    public function getJSON($resource, $params = array())
    {
        $headers = array("Content-Type" => "application/json", "Accept" => "application/json");
        $response = $this->get($resource, $headers, $params);
        return $response->json();
    }

    /**
     * POST method
     * @param string $resource
     * @param mixed $data
     * @param string $dataType: body | json
     * @return GuzzleHttp\Message\ResponseInterface
     */
    public function post($resource, $data, $dataType = 'body')
    {
        $postBody = array($dataType => $data);
        try {
            $response = $this->_client->post($resource, null, $postBody);
            return $response;
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
}
