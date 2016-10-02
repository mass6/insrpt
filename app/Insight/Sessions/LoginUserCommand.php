<?php namespace Insight\Sessions; 
/**
 * Insight Client Management Portal:
 * Date: 7/27/14
 * Time: 1:05 AM
 */

class LoginUserCommand 
{
    public $credentials;
    public $remember;

    function __construct($credentials, $remember = false)
    {
        $this->credentials = $credentials;
        $this->remember = $remember;
    }
} 