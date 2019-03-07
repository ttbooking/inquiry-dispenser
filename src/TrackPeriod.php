<?php

namespace Daniser\InquiryDispenser;

use DateTime;

/**
 * @property-read DateTime $begin
 * @property-read DateTime $end
 * @property-read bool $active
 */
class TrackPeriod
{
    /** @var DateTime $begin */
    protected $begin;

    /** @var DateTime $end */
    protected $end;

    /** @var bool $active */
    protected $active;

    /**
     * @param DateTime $begin
     * @param DateTime $end
     * @param bool $active
     */
    public function __construct(DateTime $begin, DateTime $end, $active)
    {
        $this->begin = $begin;
        $this->end = $end;
        $this->active = $active;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __isset($name)
    {
        return isset($this->$name);
    }
}
