<?php

namespace Daniser\InquiryDispenser;

use Serializable;

/**
 * @property-read Contracts\Inquiry $inquiry
 */
abstract class InquiryFactor extends Factor implements Serializable
{
    /** @var Contracts\Inquiry $inquiry */
    protected $inquiry;

    /**
     * @param Contracts\Inquiry $inquiry
     */
    public function __construct(Contracts\Inquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    /*public function __sleep()
    {
        return ['inquiry'];
    }*/

    public function serialize()
    {
        return (string)$this->inquiry->getId();
    }

    public function unserialize($serialized)
    {
        $this->inquiry = unserialize($serialized);
    }

    /**
     * @return Contracts\Inquiry
     */
    public function inquiry()
    {
        return $this->inquiry;
    }

    /**
     * @return int
     */
    protected function periodActive()
    {
        return time() - $this->inquiry->getDateTime()->getTimestamp();
    }

    /**
     * @return int
     */
    protected function weightIncrement()
    {
        return 0;
    }

    /**
     * @return int
     */
    final public function currentWeightIncrement()
    {
        return $this->periodActive() * $this->weightIncrement();
    }
}
