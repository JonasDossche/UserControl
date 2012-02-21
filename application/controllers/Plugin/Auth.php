<?php
class Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$controller = strtolower($request->getControllerName());
		
		if(!Zend_Auth::getInstance()->hasIdentity() && $controller!='index') {
			
			$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
			$redirector->gotoUrl('/index/index')->redirectAndExit();
			
		} elseif(Zend_Auth::getInstance()->hasIdentity() && $controller=='index') {
			
			$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
			$redirector->gotoUrl('/content/overview')->redirectAndExit();
			
		}
	}
}