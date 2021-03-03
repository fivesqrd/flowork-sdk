<?php
namespace Flowork;

class Builder
{
    protected $_attributes = [];

    public function attribute($key, $value)
    {
        $this->_attributes[$key] = $value;

        return $this;
    }

    public function title($value)
    {
        return $this->attribute('title', $value);
    }

    public function currency($value)
    {
        return $this->attribute('currency', $value);
    }

    public function date($value)
    {
        return $this->attribute('date', $value);
    }

    public function template($value)
    {
        return $this->attribute('template', $value);
    }

    public function number($value)
    {
        return $this->attribute('number', $value);
    }

    public function expose()
    {
        return $this->_attributes;
    }
}
