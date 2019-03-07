<?php

namespace Daniser\InquiryDispenser;

abstract class Match implements Contracts\Match, Contracts\Parameterizable, Contracts\Comparable
{
    use Concerns\Parameterized;

    /** @var Contracts\Inquiry $inquiry */
    protected $inquiry;

    /** @var Contracts\Operator $operator */
    protected $operator;

    public function __construct(Contracts\Inquiry $inquiry, Contracts\Operator $operator)
    {
        $this->inquiry = $inquiry;
        $this->operator = $operator;
    }

    public function getId()
    {
        return $this->inquiry->getId().'-'.$this->operator->getId();
    }

    /**
     * @return Contracts\Inquiry
     */
    public function inquiry()
    {
        return $this->inquiry;
    }

    /**
     * @return Contracts\Operator
     */
    public function operator()
    {
        return $this->operator;
    }

    protected static function all()
    {
        return Inquiry::applicable()->crossJoin(Operator::applicable())
            ->map(function (array $match) {
                return new static($match[0], $match[1]);
            });
    }

    final public function marry()
    {
        $this->inquiry->bind($this->operator);
    }
}
