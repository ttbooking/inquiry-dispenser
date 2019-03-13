<?php

namespace TTBooking\InquiryDispenser;

use DateTimeInterface as DateTime;
use Illuminate\Support\Facades\Cache;

/**
 * Class Factor
 * @package InquiryDispenser
 *
 * @property-read bool $active
 */
abstract class Factor implements Contracts\Factor, \ArrayAccess
{
    use Concerns\HasEvents;

    /** @var Contracts\Parameterizable $subject */
    protected $subject;

    /** @var DateTime|null $queryTime */
    protected $queryTime;

    /**
     * @param Contracts\Parameterizable $subject
     * @param DateTime|null $queryTime
     */
    public function __construct(Contracts\Parameterizable $subject, DateTime $queryTime = null)
    {
        $this->subject = $subject;
        $this->setQueryTime($queryTime);
        $this->checkout();
    }

    public function active()
    {
        return true;
    }

    protected function signature()
    {
        static $signature;
        if (!isset($signature)) $signature = md5(serialize($this));
        return $signature;
    }

    public function checkout()
    {
        $lastState = Cache::get($this->signature());
        $state = $this->active;

        if (is_null($lastState) || $state !== $lastState) {
            $this->fireFactorEvent($state ? 'activating' : 'deactivating', false);
            Cache::forever($this->signature(), $state);
            $this->fireFactorEvent($state ? 'activated' : 'deactivated', false);
        }
    }

    final public function getQueryTime()
    {
        return isset($this->queryTime) ? $this->queryTime : date_create();
    }

    final public function setQueryTime(DateTime $queryTime = null)
    {
        $this->queryTime = $queryTime;
        return $this;
    }

    final public function resetQueryTime()
    {
        return $this->setQueryTime();
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
