<?php

namespace TTBooking\InquiryDispenser\Subjects;

use Serializable;
use TTBooking\InquiryDispenser\Contracts\Subjects\Inquiry as InquiryContract;
use TTBooking\InquiryDispenser\Support\Collection;

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
            ->when(config('dispenser.group_by'), static function (Collection $inquiries, $groupBy) {
                return $inquiries->groupBy($groupBy)->map->take(config('dispenser.group_limit'))->collapse();
            })
            ->take(config('dispenser.batch_size'));
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
