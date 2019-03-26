<?php

namespace TTBooking\InquiryDispenser\Contracts\Subjects;

use TTBooking\InquiryDispenser\Contracts\Factors\Factor;

interface Operator extends Subject, Identifiable
{
    /**
     * @param bool $ready
     * @return void
     */
    public function ready($ready);

    /**
     * @param Inquiry $inquiry
     * @return Match
     */
    public function match($inquiry);

    /**
     * @param Factor $factor
     * @param int $limit
     * @return int
     */
    public function limit($factor, $limit);
}
