<?php
// TODO: needs some cleanup ..
class Aptoma_Util_Array
{
    /**
     * Sort an array of objects based on a object property.
     *
     * @param  array   $objects
     * @param  string  $propertyName
     * @param  boolean $desc
     * @return mixed   the sorted array or false if $objects wasnt an array.
     */
    public static function sortObjects($objects, $propertyName, $desc = false)
    {
        if (!is_array($objects)) {
            return false;
        }

        usort(
            $objects,
            function ($a, $b) use ($propertyName, $desc) {
                if ($a->$propertyName == $b->$propertyName) {
                    return 0;
                }

                return ($a->$propertyName < $b->$propertyName) ? ($desc ? 1 : -1) : ($desc ? -1 : 1);
            }
        );

        return $objects;
    }

    /** Checks if an array is associative
     *
     * @param  array $array The array to check
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
     * Sort a multi-dimensional array by a value in a sub-array.
     *
     * <code>
     *
     * $songs = array(
     *	  '1' => array('artist' => 'The Smashing Pumpkins', 'songname' => 'Soma'),
     *	  '2' => array('artist' => 'The Decemberists', 'songname' => 'The Island'),
     *	  '3' => array('artist' => 'Fleetwood Mac', 'songname' => 'Second-hand News')
     * );
     *
     * $songs = Aptoma_Util_Array::sortBySubValue($songs, 'artist');
     *
     * And $songs should now be:
     *
     * array(
     *	  '1' => array('artist' => 'Fleetwood Mac', 'songname' => 'Second-hand News')
     *	  '2' => array('artist' => 'The Decemberists', 'songname' => 'The Island'),
     *	  '3' => array('artist' => 'The Smashing Pumpkins', 'songname' => 'Soma'),
     * );
     *
     * </code>
     *
     * @param array  $a      The array to sort
     * @param string $subkey Which key should be used for sorting
     * @param boolean [$desc] The sorting order
     * @return array
     */
    public static function sortBySubValue($a, $subkey, $desc = false)
    {
        $b = array();
        foreach ($a as $k => $v) {
            $b[$k] = mb_strtolower($v[$subkey]);
        }
        if ($desc) {
            arsort($b);
        } else {
            asort($b);
        }
        foreach (array_keys($b) as $key) {
            $c[] = $a[$key];
        }

        return $c;
    }

    /**
     * Remove a set of keys and its value from an array.
     * Note this is NOT recursive.
     *
     * @param  array $array
     * @param  array $excludeKeys
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
     * @param  array $keys
     * @param  array $arrays
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
     * @param  array $keys
     * @param  array $array
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
     * @param  array   $array
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

    /**
     * Converts an array with dotted keys e.g (foo.bar.monkey) to an array
     * E.g array('foo.bar.monkey' => 'banana') becomes array('foo' => array('bar' => array('monkey' => 'banana')));
     * @param  array  $array
     * @return array
     */
    public static function dotStringsToDeepArray(array $array)
    {
        $assoc = array();
        foreach ($array as $dots => $value) {
            $keys = explode('.', $dots);
            $currentArray = null;
            while ($key = array_shift($keys)) {
                if (is_null($currentArray)) {
                    if (!isset($assoc[$key])) {
                        $assoc[$key] = array();
                    }
                    $currentArray = &$assoc[$key];
                    continue;
                }
                if (!isset($currentArray[$key])) {
                    $currentArray[$key] = array();
                }
                $currentArray = &$currentArray[$key];
            }

            $currentArray = $value;
            unset($currentArray);

        }
        return $assoc;
    }

    /**
     * Converts values to integers and booleans
     * Note ! modifies the values on the supplied array.
     * @param  $array
     */
    public static function convertValuesToCorrectType(&$array)
    {
        array_walk_recursive($array, function(&$val, $key) {
            if (!is_string($val)) {
                return;
            }
            if (ctype_digit($val)) {
                $val = (int)$val;
                return;
            }

            if (strtoupper($val) === 'TRUE') {
                $val = true;
                return;
            }

            if (strtoupper($val) === 'FALSE') {
                $val = false;
                return;
            }
        });
    }

}
