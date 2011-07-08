<?php
/**
 * This will handle the issues with the routing and the use of mod_rewrite and all different URLs and make the following work:
 *
 * http://localhost/com.aptoma.drfront
 * http://localhost/com.aptoma.drfront/prod
 * http://localhost/com.aptoma.drfront/prod/drfront
 */
class Aptoma_Controller_RequestRewrite extends Zend_Controller_Plugin_Abstract
{
	public function routeStartup(Zend_Controller_Request_Abstract $request)
	{
		if (Aptoma_Util_String::startsWith($_SERVER['REQUEST_URI'], dirname($_SERVER['SCRIPT_NAME']))) {
			return;
		}

		$notFound = true;
		$requestUri = array();
		$uriParts = explode('/', ltrim($_SERVER['REQUEST_URI'], '/'));
		$scriptParts = explode('/', ltrim(dirname($_SERVER['SCRIPT_NAME']), '/'));

		for ($i = 0; $i < count($uriParts); $i++) {
			if ($notFound && $uriParts[$i] != $scriptParts[$i]) {
				$notFound = false;
				$requestUri = array_merge($requestUri, array_slice($scriptParts, $i));
			}
			$requestUri[] = $uriParts[$i];
		}

		$request->setRequestUri('/' . implode('/', $requestUri));
	}
}