<?php

namespace TTBooking\InquiryDispenser\Subjects;

use Serializable;
use Illuminate\Support\Collection;
use TTBooking\InquiryDispenser\Contracts\Subjects\Inquiry as InquiryContract;

abstract class Inquiry extends Subject implements InquiryContract, Serializable
{
    public static function all($forDispense = false)
    {
        /** @var Collection|Inquiry[] $inquiries */
        $inquiries = app('dispenser.repository.inquiry')->all();

        return !$forDispense ? $inquiries : $inquiries
            ->filter(function (Inquiry $inquiry) {
                return $inquiry->is(config('dispenser.matching.inquiry.filtering'));
            })
            ->sortMultiple(config('dispenser.matching.inquiry.ordering'))
            ->groupBy('categoryId')->map->take(config('dispenser.limit_per_category'))->collapse()
            ->take(config('dispenser.batch'));
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
