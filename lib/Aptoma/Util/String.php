<?php

/**
 * Class for string utility methods.
 */
abstract class Aptoma_Util_String
{
	public static function startsWith($haystack, $needle)
	{
		$length = mb_strlen($needle);
		return (mb_substr($haystack, 0, $length) === $needle);
	}

	public static function endsWith($haystack, $needle)
	{
		$length = mb_strlen($needle);
		$start	= $length * -1; //negative
		return (mb_substr($haystack, $start) === $needle);
	}

	/**
	 * Convert underscore names e.g foo_bar to camelCase e.g fooBar
	 *
	 * @param string $underscore
	 * @return string
	 */
	public static function underscoreToCamelCase($underscore)
	{
		return preg_replace_callback('/_([a-z])/', 'Aptoma_Util_String::underscoreToCamelCaseCallback', $underscore);
	}

	/**
	 * Callback function for underscoreToCamelCase
	 * @param array $matches
	 * @return string
	 */
	private static function underscoreToCamelCaseCallback($matches)
	{
		return mb_strtoupper($matches[1]);
	}

}