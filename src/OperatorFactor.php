<?php

namespace Daniser\InquiryDispenser;

/**
 * @property-read Contracts\Operator $operator
 */
abstract class OperatorFactor extends Factor
{
    /** @var Contracts\Operator $operator */
    protected $operator;

    public function __construct(Contracts\Operator $operator)
    {
        $this->operator = $operator;
    }

    /**
     * @return Contracts\Operator
     */
    public function operator()
    {
        return $this->operator;
    }
}
