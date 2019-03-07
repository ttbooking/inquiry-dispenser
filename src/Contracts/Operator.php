<?php

namespace Daniser\InquiryDispenser\Contracts;

interface Operator extends Identifiable
{
    /**
     * @param bool $ready
     * @return void
     */
    function ready($ready);

    /**
     * @param Inquiry $inquiry
     * @return Match
     */
    function match($inquiry);

    /**
     * @param Factor $factor
     * @param int $limit
     * @return int
     */
    function limit($factor, $limit);
}
