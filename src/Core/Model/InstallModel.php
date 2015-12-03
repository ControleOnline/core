<?php

namespace Core\Model;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Core\Helper\String;
use Core\DiscoveryEntity;

class InstallModel {

    protected $_em;
    protected $_sm;

    public function __construct(\Zend\ServiceManager\ServiceManager $sm) {
        $this->setEm($sm->get('Doctrine\ORM\EntityManager'));
        $this->setSm($sm);
    }

    protected function getMetadata(array $entity, $create = true) {
        $metadata = array();
        $cmf = new DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($this->_em);
        foreach ($entity AS $e) {
            if (($this->tableExistis($this->getTableName($e)) && !$create) || (!$this->tableExistis($this->getTableName($e)) && $create)) {
                $metadata[] = $cmf->getMetadataFor($e);
            }
        }
        return $metadata;
    }

    protected function getAllMetadata(array $entity) {
        $metadata = array();
        $cmf = new DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($this->_em);
        foreach ($entity AS $e) {
            $metadata[] = $cmf->getMetadataFor($e);
        }
        return $metadata;
    }

    protected function getTableName($entity) {
        return String::decamelize(end(explode('\\', $entity)));
    }

    protected function tableExistis($table) {
        $schemaManager = $this->_em->getConnection()->getSchemaManager();
        return $schemaManager->tablesExist(array($table));
    }

    public function uninstallEntity(array $entity) {
        $metadata = $this->getMetadata($entity);
        $tool = new SchemaTool($this->_em);
        $queries = $tool->getDropSchemaSQL($metadata);
        return $this->persist($queries? : array());
    }

    public function installEntity(array $entity, $force = false) {
        if ($force) {
            $this->uninstallEntity($entity);
        }
        $metadata = $this->getMetadata($entity);
        $all_metadata = $this->getAllMetadata($entity);
        $tool = new SchemaTool($this->_em);
        $queries = array_merge(
                $tool->getCreateSchemaSql($metadata), array_filter(
                        $tool->getUpdateSchemaSql($all_metadata), function($query) {
                    if (strpos(strtoupper($query), 'DROP') === false) {
                        return $query;
                    }
                })? : array());

        return $this->persist($queries? : array());
    }

    protected function checkEntities($queries) {
        $config = $this->_sm->get('config');
        if (isset($config['doctrine']['connection']['orm_default']['params']) && $queries) {
            $dbConfig = $config['doctrine']['connection']['orm_default']['params'];
            $entity = new DiscoveryEntity($this->_em, $dbConfig, $config);
            $entity->checkEntities(true);
        }
    }

    public function updateEntity(array $entity) {

        $metadata = $this->getMetadata($entity);
        $tool = new SchemaTool($this->_em);
        $queries = $tool->getCreateSchemaSql($metadata);
        return $this->persist($queries? : array());
    }

    protected function persist(array $queries) {
        //$metadata = $cmf->getAllMetadata();
        //$metadata[0]->addForeignKeyConstraint('User', array("user_id"), array("id"), array("onUpdate" => "CASCADE","onDelete" => "CASCADE"));                        
        foreach ($queries AS $query) {
            $this->_em->getConnection()->prepare($query)->execute();
        }
        $this->checkEntities($queries);
    }

    protected function getSm() {
        return $this->_sm;
    }

    protected function setSm($sm) {
        $this->_sm = $sm;
        return $this;
    }

    protected function getEm() {
        return $this->_em;
    }

    protected function setEm($em) {
        $this->_em = $em;
        return $this;
    }

}
