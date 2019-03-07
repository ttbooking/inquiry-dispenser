<?php

namespace Daniser\InquiryDispenser\Contracts;

interface Parameterizable
{
    /**
     * Test parameterizable object factor activity.
     *
     * @param string|string[] ...$factors
     * @return bool
     */
    function is(...$factors);

    /**
     * Get parameterizable object trait(s).
     *
     * @param string|string[] ...$traits
     * @return int|int[]
     * @throws \Daniser\InquiryDispenser\Exceptions\ClassMismatchException
     */
    function get(...$traits);
}
