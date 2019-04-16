<?php

namespace TTBooking\InquiryDispenser\Support;

use Illuminate\Support\Collection as BaseCollection;
use TTBooking\InquiryDispenser\Contracts\Subjects\Subject;

class Collection extends BaseCollection
{
    /**
     * @return array
     */
    public function subjectIds()
    {
        return array_map(function (Subject $subject) {
            return $subject->getId();
        }, $this->items);
    }

    public function prepareOrdering(array $ordering, $prefix = '')
    {
        return array_reduce(array_keys($ordering), function ($prepared, $id) use ($ordering, $prefix) {
            $element = $ordering[$id];
            if (is_string($id) && is_array($element)) $element = static::prepareOrdering($element, $id);
            if (is_string($element) && !empty($prefix)) $element = "$prefix.$element";
            return array_merge($prepared, (array)$element);
        }, []);
    }

    /**
     * @param array $ordering
     * @return static
     */
    public function sortMultiple($ordering)
    {
        $items = $this->items;

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

        return new static($items);
    }
}
