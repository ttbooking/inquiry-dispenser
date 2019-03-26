<?php

namespace TTBooking\InquiryDispenser\Contracts\Subjects;

interface Inquiry extends Subject, Identifiable
{
    /**
     * @return \DateTimeInterface
     */
    public function getDateTime();

    /**
     * @param Operator $operator
     * @return void
     */
    public function bind($operator);

    /**
     * @return void
     */
    public function unbind();

    /**
     * @return Operator|null
     */
    public function bound();

    /**
     * @param Operator $operator
     * @return Match
     */
    public function match($operator);
}
