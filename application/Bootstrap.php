<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	public function _initAutoload()  {	
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('Plugin_');
		
		$autoloader = Zend_Loader_Autoloader::getInstance();		
		$autoloader->registerNamespace('Users_');
		return $autoloader;
	}
	
	public function _initRegisterPlugins() {
		$controller = Zend_Controller_Front::getInstance();
		$controller->registerPlugin(new Plugin_Auth());
		return $controller;
	}
}

