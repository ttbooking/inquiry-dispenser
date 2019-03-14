<?php

namespace TTBooking\InquiryDispenser\Contracts;

use TTBooking\InquiryDispenser\Exceptions\ItemNotFoundException;

interface Repository
{
    /**
     * @param mixed $id
     * @return mixed
     * @throws ItemNotFoundException
     */
    function get($id);

    /**
     * @return array
     */
    function all();
}
