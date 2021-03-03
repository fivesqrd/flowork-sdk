<?php

namespace Flowork\Laravel;

use Flowork\Document;

class Factory
{
    protected $_config = [];

    protected $_defaults = [];

    public function __construct(array $config, $defaults = [])
    {
        $this->_config = $config;
        $this->_defaults = $defaults;
    }

    public function document($attributes)
    {
        $document = new Document(
            $this->_config, $this->_defaults
        );

        return $document->create($attributes);
    }
}
