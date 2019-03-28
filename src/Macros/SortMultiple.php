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
