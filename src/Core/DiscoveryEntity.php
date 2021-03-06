<?php

namespace Core;

class DiscoveryEntity {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    private $entityFolder;
    private $namespace;
    private $dbConfig;
    private $config;

    public function __construct($em, $dbConfig, $config) {
        $this->em = $em;
        $this->config = $config;
        $this->entityFolder = $this->config['EntityPath'];
        $this->namespace = 'Core\\Entity\\';
        $this->dbConfig = $dbConfig;
    }

    public function prepareFolder($force = false) {
        if ($force && is_dir($this->entityFolder . DIRECTORY_SEPARATOR . 'Entity')) {
            $this->rrmdir($this->entityFolder . DIRECTORY_SEPARATOR . 'Entity');
        }
    }

    protected function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    private function getDbConfigs() {
        return array(
            'driver' => 'pdo_mysql',
            'host' => $this->dbConfig['host'],
            'port' => $this->dbConfig['port'],
            'user' => $this->dbConfig['user'],
            'password' => $this->dbConfig['password'],
            'dbname' => $this->dbConfig['dbname']
        );
    }

    private function configure() {
        $connectionParams = $this->getDbConfigs();

        $config = new \Doctrine\ORM\Configuration();
        $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver($this->entityFolder));
        $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
        $config->setProxyDir($this->entityFolder . DIRECTORY_SEPARATOR . 'proxies');
        $config->setProxyNamespace('Proxies');
        $config->setAutoGenerateProxyClasses(\Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS);
        $this->em = \Doctrine\ORM\EntityManager::create($connectionParams, $config);
    }

    public function checkEntities($force = false) {        
        if (!is_dir($this->entityFolder . 'Entity') || $force) {
            $this->prepareFolder($force);
            $this->configure();

            // custom datatypes (not mapped for reverse engineering)
            $this->em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('set', 'string');
            $this->em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');


            $driver = new RestDriver(
                    $this->em->getConnection()->getSchemaManager()
            );
            $driver->setNamespace($this->namespace);
            $driver->setBiDirecionalEntities(true);
            $this->em->getConfiguration()->setMetadataDriverImpl($driver);
            $cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory($this->em);
            $cmf->setEntityManager($this->em);
            $metadata = $cmf->getAllMetadata();
            $this->EntityGenerator($metadata);
        }

        $autoLoader = new \Zend\Loader\StandardAutoloader(array(
            'namespaces' => array(
                'Entity' => $this->entityFolder . DIRECTORY_SEPARATOR . $this->namespace,
            ),
            'fallback_autoloader' => true,
        ));

        $autoLoader->register();
    }

    private function EntityGenerator($metadata) {
        $generator = new \Doctrine\ORM\Tools\EntityGenerator();
        $generator->setUpdateEntityIfExists(true);
        $generator->setGenerateStubMethods(true);
        $generator->setGenerateAnnotations(true);
        $generator->generate($metadata, $this->entityFolder);
    }

}
