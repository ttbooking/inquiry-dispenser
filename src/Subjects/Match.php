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
     * @param Collection|Inquiry[]|Inquiry|null $inquiries
     * @param Collection|Operator[]|Operator|null $operators
     * @return Collection|static[]
     */
    public static function from($inquiries = null, $operators = null)
    {
        if (is_null($inquiries)) {
            $inquiries = collect(app(InquiryRepository::class)->all())
                ->filter(function (Inquiry $inquiry) {
                    return $inquiry->is(config('dispenser.narrowers.inquiry'));
                });
        }

        if (is_null($operators)) {
            $operators = collect(app(OperatorRepository::class)->all())
                ->filter(function (Operator $operator) {
                    return $operator->is(config('dispenser.narrowers.operator'));
                });
        }

        return collect($inquiries)->crossJoin(collect($operators))
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
