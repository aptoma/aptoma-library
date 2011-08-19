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

			case 'html':
				$viewRenderer->setNoRender(false);
				$this->enableLayout();
				break;
		}
    }

    public function postDispatch() {
    	$this->preDispatch();

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