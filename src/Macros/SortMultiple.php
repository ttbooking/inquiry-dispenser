<?php

namespace TTBooking\InquiryDispenser\Macros;

use Illuminate\Support\Collection;

/**
 * Multisort collection by given sort definition.
 *
 * @mixin Collection
 * @param mixed $ordering
 * @return Collection
 */
class SortMultiple
{
    protected static function prepareOrdering(array $ordering, $prefix = '')
    {
        return array_reduce(array_keys($ordering), function ($prepared, $id) use ($ordering, $prefix) {
            $element = $ordering[$id];
            if (is_string($id) && is_array($element)) $element = static::prepareOrdering($element, $id);
            if (is_string($element) && !empty($prefix)) $element = "$prefix.$element";
            return array_merge($prepared, (array)$element);
        }, []);
    }

    public function __invoke()
    {
        return function ($ordering) {
            $items = $this->all();

            $ordering = is_array($ordering) ? static::prepareOrdering($ordering) : func_get_args();
            if (!empty($items) && !empty($ordering)) {
                $orderingRef = [];
                foreach ($ordering as $id => &$element) {
                    if (is_string($element)) $element = $this->pluck($element)->all();
                    $orderingRef[$id] = &$element;
                }
                $keys = array_keys($items);
                call_user_func_array('array_multisort', array_merge($orderingRef, [&$keys, &$items]));
                $items = array_combine($keys, $items);
            }

            return new Collection($items);
        };
    }
}
