<?php

namespace TTBooking\InquiryDispenser\Subjects;

use Illuminate\Support\Collection;
use TTBooking\InquiryDispenser\Contracts\Subjects\Operator as OperatorContract;
use TTBooking\InquiryDispenser\Contracts\Repositories\OperatorRepository;

abstract class Operator extends Subject implements OperatorContract
{
    public static function all()
    {
        /** @var Collection|Operator[] $operators */
        $operators = app(OperatorRepository::class)->all();

        return $operators
            ->filter(function (Operator $operator) {
                return $operator->is(config('dispenser.narrowers.operator'));
            })
            ->sortMultiple(config('dispenser.ordering.operator'));
    }

    abstract public function ready($ready);
    abstract public function match($inquiry);
    abstract public function limit($factor, $limit);
}
