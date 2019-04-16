<?php

namespace TTBooking\InquiryDispenser\Subjects;

use TTBooking\InquiryDispenser\Support\Collection;
use TTBooking\InquiryDispenser\Contracts\Subjects\Match as MatchContract;

class Match extends Subject implements MatchContract
{
    /** @var Inquiry $inquiry */
    protected $inquiry;

    /** @var Operator $operator */
    protected $operator;

    public function __construct(Inquiry $inquiry, Operator $operator)
    {
        $this->inquiry = $inquiry;
        $this->operator = $operator;
        parent::__construct();
    }

    public function getId()
    {
        return $this->inquiry->getId().'-'.$this->operator->getId();
    }

    /**
     * @return Inquiry
     */
    public function inquiry()
    {
        return $this->inquiry;
    }

    /**
     * @return Operator
     */
    public function operator()
    {
        return $this->operator;
    }

    public static function all($forDispense = false)
    {
        /** @var Collection|Inquiry[] $inquiries */
        $inquiries = Inquiry::all($forDispense);

        /** @var Collection|Operator[] $operators */
        $operators = Operator::all($forDispense);

        $matches = $inquiries->crossJoin($operators)
            ->map(function (array $match) {
                return app('dispenser.match', array_combine(['inquiry', 'operator'], $match));
            });

        return !$forDispense ? $matches : $matches
            ->filter(function (Match $match) {
                return $match->is(config('dispenser.matching.match.filtering'));
            })
            ->sortMultiple(config('dispenser.matching.match.ordering'));
    }

    final public function marry()
    {
        $this->inquiry->bind($this->operator);
    }
}
