<?php

namespace Daniser\InquiryDispenser;

abstract class Inquiry implements Contracts\Inquiry, Contracts\Parameterizable, Contracts\Comparable, \Serializable
{
    use Concerns\Parameterized;

    public function serialize()
    {
        return (string)$this->getId();
    }

    abstract public function bind($operator);
    abstract public function unbind();
    abstract public function bound();
    abstract public function match($inquiry);
}
