<?php

require_once '../library/doctrine/lib/vendor/doctrine-common/lib/Doctrine/Common/ClassLoader.php';

$doctrineLib = '../library/doctrine/lib';
		
$classLoader = new ClassLoader('Doctrine\ORM', realpath($doctrineLib . '/../../lib'));
$classLoader->register();

$classLoader = new ClassLoader('Doctrine\DBAL', realpath($doctrineLib . '/../../lib/vendor/doctrine-dbal/lib'));
$classLoader->register();

$classLoader = new ClassLoader('Doctrine\Common', realpath($doctrineLib . '/../../lib/vendor/doctrine-common/lib'));
$classLoader->register();

$classLoader = new ClassLoader('Symfony', realpath(__DIR__ . '/../../lib/vendor'));
$classLoader->register();
		
$classLoader = new ClassLoader('Entities', __DIR__);
$classLoader->register();

$classLoader = new ClassLoader('Repositories', __DIR__);
$classLoader->register();

$classLoader = new ClassLoader('Proxies', __DIR__);
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__."/Entities"));
$config->setMetadataDriverImpl($driverImpl);

$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');

$connectionOptions = array(
			'driver'   => 'pdo_mysql',
			'host'     => '127.0.0.1',
			'dbname'   => 'usercontrol',
			'user'     => 'root',
			'password' => ''
	);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helpers = array(
		'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
		'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
);