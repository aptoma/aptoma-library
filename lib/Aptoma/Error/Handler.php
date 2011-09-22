<?php
class Aptoma_Error_Handler
{
	private static $isActive = false;

	/**
	 * Set a custom error handler with set_error_handler that will throw an ErrorException;
	 *
	 * @throws Exception if it is already set
	 */
	public static function enable()
	{
		if (self::$isActive) {
			//to insure that it isn't missused and placed on unnecessary placec
			throw new Exception('Error handler is already active.');
		}

		set_error_handler(array('Aptoma_Error_Handler', 'handle'));
		self::$isActive = true;
	}

	/**
	 * Disable this error handler, restores to the previously set error handler
	 *
	 * @throws Exception if it isn't set
	 */
	public static function disable()
	{
		if (!self::$isActive) {
			//to insure that it isn't missused and placed on unnecessary placec
			throw new Exception('Error handler is already disabled.');
		}

		restore_error_handler();
		self::$isActive = false;
	}

	/**
	 * Handle the error, always throws the error as an ErrorException
	 *
	 * @throws ErrorException
	 */
	public static function handle($errno, $errstr, $errfile, $errline)
	{
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}

}
