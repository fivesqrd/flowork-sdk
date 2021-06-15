<?php

namespace Flowork;

class Factory
{
    protected $_config = [];

    protected $_defaults = [];

    public function __construct(array $config, $defaults = [])
    {
        $this->_config = $config;
        $this->_defaults = $defaults;
    }

    public function auditlog($defaults = [])
    {
        return AuditLog::instance($this->_config, array_merge($this->_defaults, $defaults));
    }

    public function document($defaults = [])
    {
        return Document::instance($this->_config, array_merge($this->_defaults, $defaults));
    }
}
