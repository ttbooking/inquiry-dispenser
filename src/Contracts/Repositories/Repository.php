<?php

namespace TTBooking\InquiryDispenser\Contracts\Repositories;

use TTBooking\InquiryDispenser\Exceptions\ItemNotFoundException;

interface Repository
{
    /**
     * @param mixed $id
     * @return mixed
     * @throws ItemNotFoundException
     */
    public function get($id);

    /**
     * @return array
     */
    public function all();
}
