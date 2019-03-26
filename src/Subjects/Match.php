<?php

namespace TTBooking\InquiryDispenser\Subjects;

use Illuminate\Support\Collection;
use TTBooking\InquiryDispenser\Contracts\Subjects\Match as MatchContract;
use TTBooking\InquiryDispenser\Contracts\Subjects\Inquiry;
use TTBooking\InquiryDispenser\Contracts\Subjects\Operator;
use TTBooking\InquiryDispenser\Contracts\Repositories\InquiryRepository;
use TTBooking\InquiryDispenser\Contracts\Repositories\OperatorRepository;

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

    /**
     * @return Collection|static[]
     */
    public static function all()
    {
        /** @var Collection|Inquiry[] $inquiries */
        $inquiries = app(InquiryRepository::class)->all()
            ->filter(function (Inquiry $inquiry) {
                return $inquiry->is(config('dispenser.narrowers.inquiry'));
            });

        /** @var Collection|Operator[] $operators */
        $operators = app(OperatorRepository::class)->all()
            ->filter(function (Operator $operator) {
                return $operator->is(config('dispenser.narrowers.operator'));
            });

        return $inquiries->crossJoin($operators)
            ->map(function (array $match) {
                return new static($match[0], $match[1]);
            })->filter(function (self $match) {
                return $match->is(config('dispenser.narrowers.match'));
            });
    }

    final public function marry()
    {
        $this->inquiry->bind($this->operator);
    }
}
