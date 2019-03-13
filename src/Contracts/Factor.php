<?php

namespace TTBooking\InquiryDispenser\Contracts;

use DateTimeInterface as DateTime;

interface Factor
{
    /**
     * @return bool
     */
    function active();

    /**
     * @return DateTime
     */
    function getQueryTime();

    /**
     * @param DateTime|null $queryTime
     * @return $this
     */
    function setQueryTime(DateTime $queryTime = null);

    /**
     * @return $this
     */
    function resetQueryTime();
}
