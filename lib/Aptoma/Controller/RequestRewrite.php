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
		/*
		 * This fix will give some inconsistent urls for a zend application since baseurl will point to public folder.
		 * E.g if the app is accessed via url: http://localhost/drfront/
		 * the links in html will be shown as http://localhost/drfront/prod/drfront/public/...
		 *
		 * A more realistic fix would be removing this and use the following line
		 * $request->setBaseUrl(dirname(preg_replace('/\/public\/(.*)$/', '/$1', $_SERVER['SCRIPT_NAME'])));
		 * This will however eliminate the use of e.g and one url has to bee choosen:
		 * http://localhost/com.aptoma.drfront
		 * http://localhost/com.aptoma.drfront/prod
		 * http://localhost/com.aptoma.drfront/prod/drfront
		 *
		 * This file is kept as is for now since changeing will mostly create more problem att the customer cause most are using the old AFW way of setting things up.
		 */
	}
}