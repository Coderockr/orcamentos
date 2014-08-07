<?php

date_default_timezone_set('America/Sao_Paulo');

define('DS', DIRECTORY_SEPARATOR);

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Configuration;

$loader = require __DIR__ . '/vendor/autoload.php';

//doctrine
$config = new Configuration();
//$cache = new Cache();
$cache = new \Doctrine\Common\Cache\ApcCache();
$config->setQueryCacheImpl($cache);
$config->setProxyDir('/tmp');
$config->setProxyNamespace('EntityProxy');
$config->setAutoGenerateProxyClasses(true);

//mapping (example uses annotations, could be any of XML/YAML or plain PHP)
AnnotationRegistry::registerFile(__DIR__ . DS . 'vendor' . DS . 'doctrine' . DS . 'orm' . DS . 'lib' . DS . 'Doctrine' . DS . 'ORM' . DS . 'Mapping' . DS . 'Driver' . DS . 'DoctrineAnnotations.php');

$driver = new Doctrine\ORM\Mapping\Driver\AnnotationDriver(
    new Doctrine\Common\Annotations\AnnotationReader(),
    array(__DIR__ . DS . 'src' . DS . 'Orcamentos' . DS . 'Model')
);
$config->setMetadataDriverImpl($driver);
$config->setMetadataCacheImpl($cache);
