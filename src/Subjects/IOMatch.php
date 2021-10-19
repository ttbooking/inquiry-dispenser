<?php

namespace TTBooking\InquiryDispenser\Subjects;

use TTBooking\InquiryDispenser\Support\Collection;
use TTBooking\InquiryDispenser\Contracts\Subjects\IOMatch as IOMatchContract;

class IOMatch extends Subject implements IOMatchContract
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
        $inquiries = Inquiry::all($forDispense);
        while (!is_null($operator = Operator::all($forDispense)->shift())) {
            foreach ($inquiries as $key => $inquiry) {
                if (!isset($inquiry)) continue;
                $match = app('dispenser.match', compact('inquiry', 'operator'));
                if ($match->is(config('dispenser.matching.match.filtering'))) {
                    yield $match;
                    unset($inquiries[$key]);
                    break;
                };
            }
        }
    }

    final public function marry()
    {
        $this->inquiry->bind($this->operator);
    }
}
