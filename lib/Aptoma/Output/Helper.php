<?php
class Aptoma_Output_Helper extends Zend_Controller_Action_Helper_Abstract
{
    public function preDispatch()
    {
		$outputFormat = $this->getRequest()->getParam('format', 'html');
	   	$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');

		switch ($outputFormat) {
			case 'json':
				$this->disableLayout();
				$viewRenderer->setNoRender(true);
				break;
			case 'ajax':
				$this->disableLayout();
				break;

/*
 Setting this will override settings like disabling the layout or rendering i the controller
 			case 'html':
				$viewRenderer->setNoRender(false);
				$this->enableLayout();
				break;
*/
		}
    }

    public function postDispatch()
    {
    	$this->preDispatch();

		// If something already has done some output, leave it alone. Needed when unit-testing with Zend_Test_PHPUnit_ControllerTestCase
		// and the action is using the JSON helper (without this fix we'll get any empty JSON response when testing).
		if ($this->getResponse()->getBody() !== '') {
			return;
		}

		$outputFormat = $this->getRequest()->getParam('format', 'html');
		switch ($outputFormat) {
			case 'json':
				$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
				$this->sendJSON($viewRenderer->view->getVars());
				break;
		}

    }

    private function disableLayout()
    {
		$layout = Zend_Layout::getMvcInstance();
		$layout->disableLayout();
    }

    private function enableLayout()
    {
		$layout = Zend_Layout::getMvcInstance();
		$layout->enableLayout();
    }

    private function sendJSON($data)
    {
		$response = $this->getResponse();
		$response->setHeader('Content-Type', 'application/json');
		$response->setBody(json_encode($data));
    }
}