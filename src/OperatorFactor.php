<?php

namespace Daniser\InquiryDispenser;

/**
 * @property-read Contracts\Operator $operator
 */
abstract class OperatorFactor extends Factor
{
    /** @var Contracts\Operator $subject */
    protected $subject;

    /**
     * @return Contracts\Operator
     */
    public function operator()
    {
        return $this->subject;
    }
}
