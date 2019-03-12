<?php

namespace Daniser\InquiryDispenser;

use Daniser\InquiryDispenser\Contracts\Inquiry as InquiryContract;
use Daniser\InquiryDispenser\Contracts\TrackedParameterizable;
use Daniser\InquiryDispenser\Contracts\Comparable;
use Serializable;

abstract class Inquiry implements InquiryContract, TrackedParameterizable, Comparable, Serializable
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
