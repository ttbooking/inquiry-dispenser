<?php

namespace Daniser\InquiryDispenser\Contracts;

interface FactorTrack
{
    /**
     * Save current factor state into storage
     *
     * @param bool $force
     * @return void
     */
    function snapshot($force = false);

    function getSecondsActive();
}
