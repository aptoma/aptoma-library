<?php
// TODO: needs some cleanup ..
class Aptoma_Util_Array
{
	/**
	 * Sort an array of objects based on a object property.
	 *
	 * @param array $objects
	 * @param string $propertyName
	 * @param boolean $desc
	 * @return mixed the sorted array or false if $objects wasnt an array.
	 */
	public static function sortObjects($objects, $propertyName, $desc = false)
	{
		if (!is_array($objects)) {
			return false;
		}

		usort($objects, function($a, $b) use ($propertyName, $desc) {
			if ($a->$propertyName == $b->$propertyName) {
				return 0;
			}

			return ($a->$propertyName < $b->$propertyName) ? ($desc ? 1 : -1) : ($desc ? -1 : 1);
		});

		return $objects;
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

	/**
	 * Check all values if it validates agains FILTER_VALIDATE_INT
	 *
	 * @param array $array
	 * @return boolean
	 */
	public static function filterValidateInt(array $array)
	{
		foreach ($array as $value) {
			if (!filter_var($value, FILTER_VALIDATE_INT)) {
				return false;
			}
		}

		return true;
	}
}