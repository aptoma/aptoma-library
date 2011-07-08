<?php

class Aptoma_Util_String
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

}