<?php

namespace Insight\Portal\Connections;

use Insight\Integrations\RestClient;

class PortalRestClient extends RestClient
{
    public function __construct($consumer_key = null, $consumer_secret = null, $token = null, $token_secret = null, $base_url = null)
    {
        parent::__construct($base_url);
        $this->setOath($consumer_key, $consumer_secret, $token, $token_secret);
    }
}
