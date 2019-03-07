<?php

namespace Daniser\InquiryDispenser\Contracts;

interface Factor
{
    /**
     * @return bool
     */
    function active();
}
