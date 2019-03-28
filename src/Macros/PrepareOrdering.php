<?php

namespace TTBooking\InquiryDispenser\Macros;

use Illuminate\Support\Collection;

/**
 * @mixin Collection
 */
class PrepareOrdering
{
	public function __invoke()
	{
		return function (array $ordering, $prefix = '') {
			return array_reduce(array_keys($ordering), function ($prepared, $id) use ($ordering, $prefix) {
				$element = $ordering[$id];
				if (is_string($id) && is_array($element)) $element = static::prepareOrdering($element, $id);
				if (is_string($element) && !empty($prefix)) $element = "$prefix.$element";
				return array_merge($prepared, (array)$element);
			}, []);
		};
	}
}
