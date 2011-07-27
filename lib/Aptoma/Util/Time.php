<?php

class Aptoma_Util_Time
{
	/**
	 *  Get pretty time span duration
	 * @param $start UNIX timestamp of the starting date.
	 * @param $start UNIX timestamp of the ending date.
	 * @return string
	 */
	public static function duration($start, $end = false)
	{
		if (!$end) {
			$end = time();
		}

		$seconds = $end - $start;

		$days = floor($seconds / 60 / 60 / 24);
		$hours = $seconds / 60 / 60 %24;
		$mins = $seconds / 60 % 60;
		$secs = $seconds % 60;

		$duration = '';
		if ($days > 0) {
			$duration .= "$days days ";
		}
		if ($hours > 0) {
			$duration .= "$hours hours ";
		}
		if ($mins > 0) {
			$duration .= "$mins minutes ";
		}
		if ($secs > 0) {
			$duration .= "$secs seconds ";
		}

		$duration = trim($duration);
		if ($duration == null) {
			$duration = '0 seconds';
		}

		return $duration;
	}
}