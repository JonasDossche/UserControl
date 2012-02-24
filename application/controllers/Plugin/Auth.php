<?php
class Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$controller = strtolower($request->getControllerName());
		$action = strtolower($request->getActionName());
		
		if(!Zend_Auth::getInstance()->hasIdentity() && $controller!='index') {
			
			$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
			$redirector->gotoUrl('/index/index')->redirectAndExit();
			
		} elseif(Zend_Auth::getInstance()->hasIdentity() && $controller=='index' && $action != 'logout') {
			
			$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
			$redirector->gotoUrl('/user/overview')->redirectAndExit();
			
		}
	}
}