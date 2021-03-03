<?php

namespace Flowork;

class Proxy
{

    protected $_client;

    protected $_layout;

    protected $_attributes = [];


    public  function __construct($client, $layout = 'generic', $attributes = [])
    {
        $this->_client     = $client;
        $this->_layout     = $layout;
        $this->_attributes = $attributes;
    }

    public function send()
    {
        return $this->_client->post(
            "notification/{$this->_layout}", $this->_attributes
        );
    }

    public function render()
    {
        return $this->_client->post(
            "document/{$this->_layout}", $this->_attributes
        );
    }
}
