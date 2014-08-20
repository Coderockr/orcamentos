<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Helper\QuestionHelper;

chdir(__DIR__ . '/..');

define('DS', DIRECTORY_SEPARATOR);

require 'vendor/autoload.php';

// Load configs
$configValues = require_once 'config/config.php';

// Mapping annotation
AnnotationRegistry::registerFile('vendor' . DS . 'doctrine' . DS . 'orm' . DS . 'lib' . DS . 'Doctrine' . DS . 'ORM' . DS . 'Mapping' . DS . 'Driver' . DS . 'DoctrineAnnotations.php');

// Doctrine AnnotationDriver
$driver = new Doctrine\ORM\Mapping\Driver\AnnotationDriver(
    new Doctrine\Common\Annotations\AnnotationReader(),
    array('src' . DS . 'Orcamentos' . DS . 'Model')
);

// Doctrine cache
$configCache = ucwords(strtolower($configValues['db.options']['cache']));
$cache = new \ReflectionClass("\\Doctrine\\Common\\Cache\\{$configCache}Cache");

// Doctrine
$config = new Configuration();

// Proxies (3)
$config->setProxyDir(sys_get_temp_dir() . '/' . md5(__DIR__));
$config->setProxyNamespace('Proxies');
$config->setAutoGenerateProxyClasses(true);
$config->setMetadataDriverImpl($driver);
$config->setMetadataCacheImpl($cache->newInstance());

// EntityManager
$em = EntityManager::create($configValues['db.options'], $config);

// Console
$console = new ConsoleApplication('Gerenciamento de Orçamentos', '1.0.0');
$console->setCatchExceptions(true);
$console->setHelperSet(new HelperSet([
    'db' => new ConnectionHelper($em->getConnection()),
    'em' => new EntityManagerHelper($em),
    'dialog' => $console->getHelperSet()->get('dialog'),
    'progress' => $console->getHelperSet()->get('progress'),
    'table' => $console->getHelperSet()->get('table'),
    new FormatterHelper(),
    new DebugFormatterHelper(),
    new ProcessHelper(),
    new QuestionHelper()
]));

$console->addCommands(array(
    // ORM Commands
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
    new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),

    // Migrations Commands
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\LatestCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand(),

    // Orcamentos Commands
    new Orcamentos\Console\InitializeCommand(),
    new Orcamentos\Console\ResetPasswordCommand()
));
$console->run();
