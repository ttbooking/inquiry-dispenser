<?php

namespace TTBooking\InquiryDispenser\Contracts\Repositories;

use Illuminate\Support\Collection;
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
     * @return Collection
     */
    public function all();
}
