<?php

namespace TTBooking\InquiryDispenser\Contracts;

interface Inquiry extends Identifiable
{
    /**
     * @return \DateTimeInterface
     */
    function getDateTime();

    /**
     * @param Operator $operator
     * @return void
     */
    function bind($operator);

    /**
     * @return void
     */
    function unbind();

    /**
     * @return Operator|null
     */
    function bound();

    /**
     * @param Operator $operator
     * @return Match
     */
    function match($operator);
}
