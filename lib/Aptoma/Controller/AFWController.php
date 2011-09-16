<?php
class Aptoma_Controller_AFWController extends Zend_Controller_Action
{
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
	{
		$this->_loadAFW();
		parent::__construct($request, $response, $invokeArgs);
	}

	protected function _loadAFW()
	{
		require(APPLICATION_PATH . '/../WEB-INF/load.php');
		AFW::disableExceptionHandling();
		AFW::disableErrorHandling();

		require_once('Propel/Propel.php');
		Propel::init(APPLICATION_PATH . '/configs/propel/drfront-conf.php');
	}
}