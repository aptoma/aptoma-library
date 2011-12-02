<?php

class Aptoma_Util_System
{
	/**
	* Wrapper for php exec command.
	*
	* @param $command string - The command that will be executed.
	* @param $exitcode int - If argument is present, then the return status of the executed command will be written to this variable.
	* @return String output from the command.
	* @throws Aptoma_Util_SystemException if the exec return value wasnt 0. The exception error code will be set to the return value of the exec command.
	*/
	public static function execCommand($command, &$exitcode = null)
	{
		$output = array();

		exec($command, $output, $exitcode);

		$output = implode("\n", $output);

		switch ($exitcode) {
			case 2:
				throw new Aptoma_Util_SystemException('Incorrect usage (cmd = ' . $command  . ')', $exitcode);
				break;
			case 126:
				throw new Aptoma_Util_SystemException('Command found but is not executable (cmd = ' . $command  . ')', $exitcode);
				break;
			case 127:
				throw new Aptoma_Util_SystemException('Command not found (cmd = ' . $command  . ')', $exitcode);
				break;
		}

		if ($exitcode !== 0) {
			throw new Aptoma_Util_SystemException('Command did not exit properly (cmd = ' . $command  . ') (code = ' . $exitcode . ') output: ' . $output, $exitcode);
		}

		return $output;
	}

}