<?php

namespace Insight\Mailers;

use Illuminate\Support\Facades\Config;

class MandrillClient
{
    /**
     * @var \Mandrill
     */
    private $mandrill;


    public function __construct()
    {
        $mandrill       = new \Mandrill(Config::get('services.mandrill.secret'));
        $this->mandrill = $mandrill;
    }

    public function getClient()
    {
        return $this->mandrill;
    }
}
