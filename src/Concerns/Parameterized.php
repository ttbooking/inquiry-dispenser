<?php

namespace TTBooking\InquiryDispenser\Concerns;

use DateTimeInterface as DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use TTBooking\InquiryDispenser\Contracts;
use TTBooking\InquiryDispenser\Factor;
use TTBooking\InquiryDispenser\Reduce;

trait Parameterized
{
    /** @var string[] $factors */
    protected $factors = [];                            // alias => class

    /** @var string $reducerCollection */
    protected $reducerCollection = Reduce::class;

    /** @var callable[] $traits */
    protected $traits = [];                             // trait => reduction

    /** @var callable $defaultTraitReduction */
    protected $defaultTraitReduction = 'add';

    /** @var array $defaults */
    protected $defaults = [];                           // trait => default

    /** @var mixed $defaultTraitValue */
    protected $defaultTraitValue = 0;

    /** @var string $filter */
    protected static $filter = '';

    /**
     * @param DateTime|null $queryTime
     * @return Collection|Contracts\Factor[]
     */
    public function factors(DateTime $queryTime = null)
    {
        return collect($this->factors)->map(function ($factor) use ($queryTime) {
            return new $factor($this, $queryTime);
        });
    }

    /**
     * @param string $name
     * @param DateTime|null $queryTime
     * @return Contracts\Factor
     */
    public function factor($name, DateTime $queryTime = null)
    {
        return $this->factors($queryTime)[$name];
    }

    /**
     * @param string $name
     * @return callable
     */
    public function reducer($name)
    {
        $reducer = $this->reducerCollection . '::' . $name;
        return is_callable($reducer) ? $reducer : $name;
    }

    /**
     * @return Collection|callable[]
     */
    public function traits()
    {
        return collect(array_flip_numkeys($this->traits, $this->defaultTraitReduction));
    }

    /**
     * @return Collection
     */
    public function defaults()
    {
        $defaults = array_flip_numkeys($this->defaults, $this->defaultTraitValue);
        $traits = $this->traits()->all();
        return collect(array_intersect_key($defaults, $traits) + array_fill_keys(array_keys($traits), $this->defaultTraitValue));
    }

    /**
     * @return Collection|static[]
     */
    protected static function all()
    {
        return collect();
    }

    /**
     * @return Collection|static[]
     */
    public static function applicable()
    {
        return static::all()->filter(function (self $subject) {
            return $subject->is(static::$filter);
        });
    }

    public static function checkout()
    {
        foreach (static::all() as $inquiry) $inquiry->fireFactorEvents();
    }

    public function fireFactorEvents()
    {
        foreach ($this->factors() as $factor) $factor->checkout();
    }

    final public function was($factors, DateTime $queryTime = null)
    {
        return array_reduce((array)$factors, function ($was, $_factor) use ($queryTime) {
            $inv = $_factor !== ($factor = ltrim($_factor, '!'));
            return $inv xor $was
                && array_key_exists($factor, $this->factors())
                && $this->factor($factor, $queryTime)->active();
        }, true);
    }

    final public function getAsOf($traits, DateTime $queryTime = null)
    {
        if (empty($traits)) $traits = $this->traits()->keys();

        // journey begins...
        $result =

            // narrow trait name/reduction array to only requested traits
            $this->traits()->only($traits)

            // calculate trait values via reduction
            ->map(function ($reduction, $trait) use ($queryTime) {

                // apply factors to subject
                return $this->factors($queryTime)

                    // leave only ones that affect given trait
                    ->filter(function (Factor $factor) use ($trait) {
                        return Arr::exists($factor, $trait);
                    })

                    // filter out inactive
                    ->where('active', true)

                    // extract trait components
                    ->pluck($trait)

                    // reduce them to a single value - A TRAIT
                    ->reduce(

                        // retrieve reducer
                        $this->reducer($reduction),

                        // retrieve default trait value
                        $this->defaults()[$trait]

                    );
            });

        return is_array($traits) ? $result : $result->first();
    }

    final public function is(...$factors)
    {
        if (isset($factors[0]) && is_array($factors[0])) $factors = $factors[0];

        return $this->was($factors);
    }

    final public function get(...$traits)
    {
        $scalarResult = false;

        if (isset($traits[0]) && is_array($traits[0])) $traits = $traits[0];
        elseif (count($traits) === 1) $scalarResult = true;

        $result = $this->getAsOf($traits);

        return $scalarResult ? $result->first() : $result;
    }

    public function __call($name, array $arguments)
    {
        if (1 === preg_match('/^is(\w+)/', $name, $matches)) {
            return $this->is(lcfirst($matches[1]));
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->traits()->all())) {
            return $this->get($name);
        }
    }
}
