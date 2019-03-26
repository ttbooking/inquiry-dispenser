<?php

namespace TTBooking\InquiryDispenser\Contracts\Subjects;

interface Match extends Subject, Identifiable
{
    /**
     * @return void
     */
    public function marry();
}
