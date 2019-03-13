<?php

namespace TTBooking\InquiryDispenser\Contracts;

interface Identifiable
{
    /**
     * @return mixed
     */
    function getId();

    /**
     * @param mixed $id
     * @return static
     */
    static function fromId($id);
}
