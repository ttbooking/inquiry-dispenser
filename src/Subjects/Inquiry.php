<?php

namespace TTBooking\InquiryDispenser\Subjects;

use Serializable;
use Illuminate\Support\Collection;
use TTBooking\InquiryDispenser\Contracts\Subjects\Inquiry as InquiryContract;
use TTBooking\InquiryDispenser\Contracts\Repositories\InquiryRepository;

abstract class Inquiry extends Subject implements InquiryContract, Serializable
{
    public static function all()
    {
        /** @var Collection|Inquiry[] $inquiries */
        $inquiries = app(InquiryRepository::class)->all();

        return $inquiries
            ->filter(function (Inquiry $inquiry) {
                return $inquiry->is(config('dispenser.narrowers.inquiry'));
            })
            ->sortMultiple(config('dispenser.ordering.inquiry'));
    }

    public function serialize()
    {
        return (string)$this->getId();
    }

    abstract public function bind($operator);
    abstract public function unbind();
    abstract public function bound();
    abstract public function match($inquiry);
}
