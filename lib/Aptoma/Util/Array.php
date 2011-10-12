<?php
// TODO: needs some cleanup ..
class Aptoma_Util_Array
{

	/** Sorts an array of objects by a specific property
	 *
	 * @author stefan@aptoma.com, michael@aptoma.com
	 * @param array $objectarray List of objects
	 * @param string|num $sortproperty Search property (e.g. "created") or function (e.g. "getCreated()")
	 * @param bool $desc (optional) Sort array desc
	 * @return array The sorted array
	 *
	 */
	public static function sortObjects($objectarray, $sortproperty, $desc = false)
	{
		if (is_array($sortproperty)) {
			return self::sortObjectsByPriority($objectarray, $sortproperty, $desc);
		}
		if (isset($objectarray[0])) {
			if (mb_strpos($sortproperty, '()') > 0) {
				$is_numeric = is_numeric($objectarray[0]->{str_replace('()', '', $sortproperty)}());
			} else {
				$is_numeric = is_numeric($objectarray[0]->{$sortproperty});
			}
		}
		if (isset($objectarray[0]) &&$is_numeric) {
			if ($desc) {
				$code = ' return($b->'.$sortproperty.' - $a->'.$sortproperty.');';
			} else {
				$code = ' return($a->'.$sortproperty.' - $b->'.$sortproperty.');';
			}
		} else {
			if ($desc) {
				$code = 'if ($a->'.$sortproperty.' < $b->'.$sortproperty.') return 1;
					if ($a->'.$sortproperty.' > $b->'.$sortproperty.') return -1;
					return 0;';
			} else {
				$code = 'if ($a->'.$sortproperty.' < $b->'.$sortproperty.') return -1;
					if ($a->'.$sortproperty.' > $b->'.$sortproperty.') return 1;
					return 0;';
			}
		}
		if (self::isAssociative($objectarray)) {
			uasort($objectarray, create_function('$a, $b', $code));
		} else {
			usort($objectarray, create_function('$a, $b', $code));
		}
		return $objectarray;
	}

	/** Sorts an array of objects by a priority list of properties
	 *
	 * @author micheal@aptoma.com
	 * @param array $objectarray List of objects
	 * @param array $sortproperty Priority list of search properties (e.g. "created") or function (e.g. "getCreated()")
	 * @param bool $desc (optional) Sort array desc
	 * @return array The sorted array
	 */
	public static function sortObjectsByPriority($objectarray, $sortproperties, $desc = false)
	{
		$cmp_op = ($desc) ? '<' : '>';

		$code = '';
		foreach ($sortproperties as $prop) {
			$code .= 'if ($a->'.$prop.' != $b->'.$prop.') return $a->'.$prop.' '.$cmp_op.' $b->'.$prop.';';
		}
		$code .= 'return 0;';

		if (self::isAssociative($objectarray)) {
			uasort($objectarray, create_function('$a, $b', $code));
		} else {
			usort($objectarray, create_function('$a, $b', $code));
		}
		return $objectarray;
	}

	/** Checks if an array is associative
	 *
	 * @param array $array The array to check
	 * @return bool
	 */
	public static function isAssociative($array)
	{
		if (!is_array($array)) {
			return false;
		}
		foreach (array_keys($array) as $k => $v) {
			if ($k !== $v) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Sorts two-dimension array by key name
	 *
	 * <code>
	 * $a = array();
	 * $a['name'] = array('Hansen', 'Jensen', 'Fransen');
	 * $a['age'] = array(24, 56, 35);
	 *
	 * $a = Aptoma_Util_Array::sortByField($a, 'name');
	 * returns
	 * $a['name'] = array('Fransen', 'Hansen', 'Jensen');
	 * $a['age'] = array(35, 24, 356);
	 * </code>
	 *
	 * @param array $array Array to sort
	 * @param string $search_field_key Key name of array to sort
	 * @param boolean $desc Order
	 * @return array
	 */
	public static function sortByField($array, $search_field_key, $desc = false)
	{
		$a = $b = array();
		$b_key = "";
		foreach ($array as $k => $v) {
			if ($k == $search_field_key) {
				$a = $v;
			} else {
				$b = $v;
				$b_key = $k;
			}
		}

		array_multisort($a, $b);

		if ($desc) {
			$a = array_reverse($a);
			$b = array_reverse($b);
		}
		return array($search_field_key => $a, $b_key => $b);
	}

	/**
	 * Remove a set of keys and its value from an array.
	 * Note this is NOT recursive.
	 *
	 * @param array $array
	 * @param array $excludeKeys
	 * @return array
	 */
	public static function excludeKeys(array $array, array $excludeKeys)
	{
		foreach (array_keys($array) as $key) {
			if (in_array($key, $excludeKeys)) {
				unset($array[$key]);
			}
		}

		return $array;
	}

	/**
	 * Extract specific keys with its values from each array in $arrays
	 * This is not recursive / deep
	 *
	 * @param array $keys
	 * @param array $arrays
	 * @return array
	 */
	public static function extractKeysFromList(array $keys, array $arrays)
	{
		$result = array();
		foreach ($arrays as $array) {
			$result[] = self::extractKeys($keys, $array);
		}

		return $result;
	}

	/**
	 * Extract specific keys with its values an array
	 * This is not recursive / deep
	 *
	 * @param array $keys
	 * @param array $array
	 * @return array
	 */
	public static function extractKeys(array $keys, array $array)
	{
		$result = array();
		foreach ($keys as $key) {
			if (isset($array[$key])) {
				$result[$key] = $array[$key];
			}
		}

		return $result;
	}
}