<?php

namespace TTBooking\InquiryDispenser;

abstract class Suggestion implements Contracts\Suggestion
{
    abstract public function accept();
    abstract public function reject();
}
