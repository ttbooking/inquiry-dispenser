<?php

namespace Daniser\InquiryDispenser;

/**
 * @property-read Contracts\Match $match
 */
abstract class MatchFactor extends Factor
{
    /** @var Contracts\Match $match */
    protected $match;

    public function __construct(Contracts\Match $match)
    {
        $this->match = $match;
    }

    /**
     * @return Contracts\Match
     */
    public function match()
    {
        return $this->match;
    }
}
