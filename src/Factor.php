<?php

namespace Daniser\InquiryDispenser;

/**
 * Class Factor
 * @package InquiryDispenser
 *
 * @property-read bool $active
 */
abstract class Factor implements Contracts\Factor, \ArrayAccess
{
    use Concerns\HasEvents;

    public function active()
    {
        return true;
    }

    final public function __isset($name)
    {
        return method_exists($this, $name);
    }

    final public function __get($name)
    {
        return $this->$name();
    }

    final public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    final public function offsetGet($offset)
    {
        return $this->$offset;
    }

    final public function offsetSet($offset, $value)
    {
    }

    final public function offsetUnset($offset)
    {
    }
}
