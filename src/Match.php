<?php

namespace TTBooking\InquiryDispenser;

use Illuminate\Support\Collection;

abstract class Match implements Contracts\Match, Contracts\Parameterizable, Contracts\Comparable
{
    use Concerns\Parameterized;

    /** @var Contracts\Inquiry $inquiry */
    protected $inquiry;

    /** @var Contracts\Operator $operator */
    protected $operator;

    public function __construct(Contracts\Inquiry $inquiry, Contracts\Operator $operator)
    {
        $this->inquiry = $inquiry;
        $this->operator = $operator;
    }

    public function getId()
    {
        return $this->inquiry->getId().'-'.$this->operator->getId();
    }

    /**
     * @return Contracts\Inquiry
     */
    public function inquiry()
    {
        return $this->inquiry;
    }

    /**
     * @return Contracts\Operator
     */
    public function operator()
    {
        return $this->operator;
    }

    /**
     * @param Collection|Inquiry[]|Inquiry|null $inquiries
     * @param Collection|Operator[]|Operator|null $operators
     * @return Collection|static[]
     */
    public static function from($inquiries = null, $operators = null)
    {
        if (is_null($inquiries)) $inquiries = Inquiry::applicable();
        if (is_null($operators)) $operators = Operator::applicable();
        return collect($inquiries)->crossJoin(collect($operators))
            ->map(function (array $match) {
                return new static($match[0], $match[1]);
            });
    }

    public static function all()
    {
        return static::from();
    }

    final public function marry()
    {
        $this->inquiry->bind($this->operator);
    }
}
