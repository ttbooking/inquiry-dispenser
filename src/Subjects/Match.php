<?php

namespace TTBooking\InquiryDispenser\Subjects;

use Illuminate\Support\Collection;
use TTBooking\InquiryDispenser\Contracts\Subjects\Match as MatchContract;
use TTBooking\InquiryDispenser\Contracts\Repositories\MatchRepository;
use TTBooking\InquiryDispenser\Subjects\Inquiry;
use TTBooking\InquiryDispenser\Subjects\Operator;

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

    public static function all()
    {
        /** @var Collection|Match[] $matches */
        $matches = app(MatchRepository::class)->all();

        return $matches
            ->filter(function (Match $match) {
                return $match->is(config('dispenser.narrowers.match'));
            })
            ->sortMultiple(config('dispenser.ordering.match'));
    }

    final public function marry()
    {
        $this->inquiry->bind($this->operator);
    }
}
