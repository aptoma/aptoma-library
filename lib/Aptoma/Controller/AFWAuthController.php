<?php
class Aptoma_Controller_AFWAuthController extends Aptoma_Controller_AFWController
{
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
	{
		$this->_loadAFW();
        $this->setRequest($request)
             ->setResponse($response)
             ->_setInvokeArgs($invokeArgs);
        $this->_helper = new Zend_Controller_Action_HelperBroker($this);

	    if (!$this->isAuthenticated()) {
			$this->_forward('login');
		}

		$this->_helper->layout->setLayout('index');
        $this->init();
	}

	public function loginAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer('shared/login', null, true);
	}

	/**
	 * Check if this request is authenticated with AFWAuth
	 *
	 * @return boolean
	 */
	protected function isAFWAuthenticated()
	{
		if (AFWExtConfig::get("AUTH_DISABLED")) {
			return true;
		}

		if (!isset($this->AFWAuth) || !($this->AFWAuth instanceof AFWAuth)) {
			$this->AFWAuth = new AFWAuth('', AFWGlobal::singleton('DrFrontProcessor'));
			AFWGlobal::setVar('AFWAuth', $this->AFWAuth);
		}

		return $this->AFWAuth->auth();
	}

	/**
	 *
	 * Check is this request is authenticated. It makes use of getPublicAction method
	 * and isAFWAuthenticated to determine if the request is authenticated
	 *
	 * @see getPublicActions
	 * @see isAFWAuthenticated
	 * @return boolean
	 */
	protected function isAuthenticated()
	{
		if (in_array($this->_request->getActionName(), $this->getPublicActions())) {
			return true;
		}

		return $this->isAFWAuthenticated();
	}

	/**
	 * Returns a array with strings containing action names that doesnt require authentication.
	 *
	 * @return array list of action names that doesnt requires authentication
	 */
	public function getPublicActions()
	{
		return array();
		//ovverride this to add public actions that doesn't require authentication.
	}
}