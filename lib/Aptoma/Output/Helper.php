<?php
class Aptoma_Output_Helper extends Zend_Controller_Action_Helper_Abstract
{
    public function postDispatch()
    {
		$outputFormat = $this->getRequest()->getParam('format', 'html');
		switch ($outputFormat) {
			case 'json':
				$this->disableLayout();
	    		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
				$viewRenderer->setNoRender(true);

				$this->sendJSON($viewRenderer->view->getVars());
				break;
			case 'ajax';
				$this->disableLayout();
				break;
		}
    }

    private function disableLayout()
    {
		$layout = Zend_Layout::getMvcInstance();
		$layout->disableLayout();
    }

    private function sendJSON($data)
    {
		$response = $this->getResponse();
		$response->setHeader('Content-Type', 'application/json');
		$response->setBody(json_encode($data));
    }
}