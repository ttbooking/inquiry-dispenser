<?php

namespace Daniser\InquiryDispenser\Contracts;

interface Suggestion
{
    /**
     * @return void
     */
    function accept();

    /**
     * @return void
     */
    function reject();
}
