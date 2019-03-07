<?php

namespace Daniser\InquiryDispenser;

class Reduce
{
    public static function first($a, $b)
    {
        return $a;
    }

    public static function last($a, $b)
    {
        return $b;
    }

    public static function add($a, $b)
    {
        return $a + $b;
    }

    public static function mult($a, $b)
    {
        return $a * $b;
    }

    public static function concat($a, $b)
    {
        return $a . $b;
    }

    public static function conjunct($a, $b)
    {
        return $a & $b;
    }

    public static function disjunct($a, $b)
    {
        return $a | $b;
    }

    public static function xdisjunct($a, $b)
    {
        return $a ^ $b;
    }
}
