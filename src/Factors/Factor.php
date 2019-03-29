<?php

namespace TTBooking\InquiryDispenser\Factors;

use DateTimeInterface;
use Illuminate\Support\Facades\Cache;
use TTBooking\InquiryDispenser\Concerns\HasEvents;
use TTBooking\InquiryDispenser\Contracts\Factors\Factor as FactorContract;
use TTBooking\InquiryDispenser\Contracts\Subjects\Subject;

/**
 * Class Factor
 * @package InquiryDispenser
 *
 * @property-read bool $active
 */
abstract class Factor implements FactorContract
{
    use HasEvents;

    /** @var Subject $subject */
    protected $subject;

    /** @var DateTimeInterface|null $queryTime */
    protected $queryTime;

    /**
     * @param Subject $subject
     * @param DateTimeInterface|null $queryTime
     */
    public function __construct(Subject $subject, DateTimeInterface $queryTime = null)
    {
        $this->subject = $subject;
        $this->setQueryTime($queryTime);
        //$this->checkout();
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

    final public function setQueryTime(DateTimeInterface $queryTime = null)
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
