<?php

namespace Daniser\InquiryDispenser\Contracts;

interface FactorTrack
{
    function snapshot();

    function getSecondsActive();
}
