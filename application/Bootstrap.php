<?php
use Doctrine\Common\ClassLoader;
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	public function _initAutoload()  {
		//Register plugin namespace
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('Plugin_');			
		
		//Register doctrine namespaces
		$doctrineConfig = $this->getOption('doctrine');
		require_once $doctrineConfig['directories']['classloader'];
				
		$classLoader = new ClassLoader('Doctrine\ORM', $doctrineConfig['directories']['orm']);
		$classLoader->register();
		$classLoader = new ClassLoader('Doctrine\DBAL', $doctrineConfig['directories']['dbal']);
		$classLoader->register();
		$classLoader = new ClassLoader('Doctrine\Common', $doctrineConfig['directories']['common']);
		$classLoader->register();
		$classLoader = new ClassLoader('Symfony', $doctrineConfig['directories']['symfony']);
		$classLoader->register();		
		$classLoader = new ClassLoader('Validators', __DIR__);
		$classLoader->register();
		$classLoader = new ClassLoader('Entities', $doctrineConfig['directories']['entities']);
		$classLoader->register();
		$classLoader = new ClassLoader('Repositories', $doctrineConfig['directories']['repositories']);
		$classLoader->register();
		$classLoader = new ClassLoader('Proxies', $doctrineConfig['directories']['proxies']);
		$classLoader->register();
		$classLoader = new ClassLoader('Plugins', $doctrineConfig['directories']['plugins']);
		$classLoader->register();
		
		
		return $autoloader;
	}
	
	protected function _initViewHelpers()
	{
		$view = new Zend_View();
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
	
		$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
		$viewRenderer->setView($view);
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
	}
	
	public function _initRegisterPlugins() {
		//Register plugin
		$controller = Zend_Controller_Front::getInstance();
		$controller->registerPlugin(new Plugins\Auth());
		return $controller;
	}
	
	public function _initDoctrine() {		
		$cache = new \Doctrine\Common\Cache\ArrayCache();
		$config = new Doctrine\ORM\Configuration();
		$config->setMetadataCacheImpl($cache);
		
		$driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__."/entities"));
		$config->setMetadataDriverImpl($driverImpl);
		
		$config->setProxyDir(__DIR__ . '/proxies');
		$config->setProxyNamespace('Proxies');	
		
		$doctrineConfig = $this->getOption('doctrine');
				
		$connectionOptions = array(
				'driver'   => $doctrineConfig['database']['driver'],
				'host'     => $doctrineConfig['database']['host'],
				'dbname'   => $doctrineConfig['database']['dbname'],
				'user'     => $doctrineConfig['database']['user'],
				'password' => $doctrineConfig['database']['password']
		);		
		
		$em = Doctrine\ORM\EntityManager::create($connectionOptions, $config);	
		Zend_Registry::set('EntityManager',$em);
		
		return $em;
	}
}

