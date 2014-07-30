<?php

namespace Coop\Objects;

abstract class Objects {

	/**
	 * multidimension array sort by field
	 *
	 * @param $array
	 * @param $fieldName
	 *
	 * @return mixed
	 */
	public function fsort(&$array, $fieldName) {
		$sortFunction = function($a, $b) use ($fieldName) {
			if ($a[$fieldName] == $b[$fieldName]) {
				return 0;
			}
			return ($a[$fieldName] < $b[$fieldName]) ? -1 : 1;
		};
		usort($array, $sortFunction);
		return $array;
	}
}