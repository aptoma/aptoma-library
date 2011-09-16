<?php

class Aptoma_Util_System
{
	/**
	* Wrapper for php exec command.
	*
	* @param $command String - the command line to exec.
	* @return String output from the command.
	* @throws Aptoma_Util_SystemException if the exec return value wasnt 0. The exception error code will be set to the return value of the exec command.
	*/
	public static function execCommand($command)
	{
		$output = array();
		$returnValue = -1;

		exec($command, $output, $returnValue);
		$output = implode("\n", $output);

		if ($returnValue !== 0) {
			if ($returnValue == 127) {
				throw new Aptoma_Util_SystemException('execCommand(' . $command . '): command not found.', $returnValue);
			}

			throw new Aptoma_Util_SystemException('execCommand(' . $command. ") did not exit properly.\nExit code was: " . $returnValue . "\nOutput: " . $output, $returnValue);
		}

		return $output;
	}

}