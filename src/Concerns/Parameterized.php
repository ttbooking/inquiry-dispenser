<?php

namespace Daniser\InquiryDispenser\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Daniser\InquiryDispenser\Contracts;
use Daniser\InquiryDispenser\Factor;
use Daniser\InquiryDispenser\Reduce;

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
     * @return Collection|Contracts\Factor[]
     */
    public function factors()
    {
        return collect($this->factors)->map(function ($factor) {
            return new $factor($this);
        });
    }

    /**
     * @param string $name
     * @return Contracts\Factor
     */
    public function factor($name)
    {
        return $this->factors()[$name];
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

    public function fireObservers()
    {
        foreach ($this->factors() as $name => $factor) {
            /** @var Factor $factor */
            $factor = new $factor($this);
            $factor->notify($this, $factor->active());
        }
    }

    /**
     * @param string|string[] ...$factors
     * @return bool
     */
    final public function is(...$factors)
    {
        if (isset($factors[0]) && is_array($factors[0])) $factors = $factors[0];
        return array_reduce($factors, function ($is, $_factor) {
            $inv = $_factor !== ($factor = ltrim($_factor, '!'));
            return $inv xor $is
                && array_key_exists($factor, $this->factors())
                && $this->factor($factor)->active();
        }, true);
    }

    /**
     * @param string[]|string ...$traits
     * @return Collection|mixed[]|mixed
     */
    final public function get(...$traits)
    {
        $scalarResult = false;

        if (isset($traits[0]) && is_array($traits[0])) $traits = $traits[0];
        elseif (count($traits) === 1) $scalarResult = true;

        if (empty($traits)) $traits = $this->traits()->keys();

        // journey begins...
        $result =

            // narrow trait name/reduction array to only requested traits
            $this->traits()->only($traits)

            // calculate trait values via reduction
            ->map(function ($reduction, $trait) {

                // apply factors to subject
                return $this->factors()

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
