<?php
require_once(dirname(__FILE__) . '/../../../lib/Aptoma/Error/Handler.php');

class Aptoma_Error_HandlerTest extends PHPUnit_Framework_TestCase
{

	public function testEnable()
	{
		//we need to do all tests in one function since phpunits error_handler will screw things up
		//using try catch since we cant use multiple //$this->setExpectedException(); in one function

		$enableException 		= false;
		$doubleEnableException 	= false;
		$disableException 		= false;
		$doubleDisableException = false;

		Aptoma_Error_Handler::enable();

		//Test enable
		try {
			trigger_error('Test Error', E_USER_ERROR);
		} catch (ErrorException $e) {
			$enableException = true;
		}

		$this->assertTrue($enableException);

		//Test double enable
		try {
			Aptoma_Error_Handler::enable();
		} catch (Exception $e) {
			$doubleEnableException = $e->getMessage() === 'Error handler is already active.';
		}

		$this->assertTrue($doubleEnableException);

		//Test disable
		Aptoma_Error_Handler::disable();

		try {
			trigger_error('Test Error', E_USER_ERROR);
		} catch (PHPUnit_Framework_Error $e) {
			$disableException = true;
		}

		$this->assertTrue($disableException);

		//Test double disable
		try {
			Aptoma_Error_Handler::disable();
		} catch (Exception $e) {
			$doubleDisableException = $e->getMessage() === 'Error handler is already disabled.';
		}

		$this->assertTrue($doubleDisableException);
	}
}