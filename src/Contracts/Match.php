<?php

namespace TTBooking\InquiryDispenser\Contracts;

interface Match extends Identifiable
{
    /**
     * @return Suggestion
     */
    //function suggest();

    /**
     * @return void
     */
    function marry();
}
