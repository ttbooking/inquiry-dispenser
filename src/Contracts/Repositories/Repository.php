<?php

namespace TTBooking\InquiryDispenser\Contracts\Repositories;

use TTBooking\InquiryDispenser\Support\Collection;
use TTBooking\InquiryDispenser\Contracts\Subjects\Subject;
use TTBooking\InquiryDispenser\Exceptions\SubjectNotFoundException;

interface Repository
{
    /**
     * @param mixed $id
     * @return Subject
     * @throws SubjectNotFoundException
     */
    public function get($id);

    /**
     * @return Collection|Subject[]
     */
    public function all();
}
