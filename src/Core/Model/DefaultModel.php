<?php

namespace Core\Model;

use Doctrine\ORM\Tools\Pagination\Paginator;

class DefaultModel {

    /**
     * @var \Doctrine\ORM\EntityManager $_em
     */
    protected $_em;

    /**
     * @var \Doctrine\ORM\EntityRepository          
     */
    protected $entity;

    /**
     * @var \Zend\ServiceManager\ServiceManager    
     */
    protected $_sm;
    protected $entity_name;
    protected $children_entity_name;
    protected $rows;
    protected $alias = [];
    protected $join = [];
    protected $current_deep = 0;
    protected $max_deep = 0;
    protected $config;

    public function __construct(\Zend\ServiceManager\ServiceManager $sm, $entity = null) {
        if (!$entity) {
            $namespace = str_replace('Model', 'Entity', explode('\\', get_called_class()));
            $namespace[] = str_replace('Entity', '', array_pop($namespace));
            $entity = implode('\\', $namespace);
        }
        $this->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
        $this->setSm($sm);
        $this->setEntity($entity);
    }

    /**
     * @return \Doctrine\ORM\EntityManager 
     */
    public function getEntityManager() {
        return $this->_em;
    }

    public function setEntityManager(\Doctrine\ORM\EntityManager $em) {
        $this->_em = $em;
        return $this;
    }

    public function getConfig() {
        return $this->config;
    }

    public function setConfig($config) {
        $this->config = $config;
        return $this;
    }

    public function getMaxDeep() {
        return $this->max_deep;
    }

    public function setMaxDeep($max_deep) {
        $this->max_deep = $max_deep;
        return $this;
    }

    public function setEntity($entity) {
        $this->entity = class_exists($entity) ? $this->_em->getRepository($entity) : null;
        $this->entity_name = $entity;
        return $this;
    }

    public function getEntity() {
        return $this->entity;
    }

    public function getMetadata($entity_name) {
        $cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($this->_em);
        return $cmf->getMetadataFor($entity_name);
    }

    public function form($entity, $params = false) {
        $return = [];
        $return['form_name'] = strtolower($entity);
        $metadata = $this->getMetadata($this->entity_name);
        if ($metadata->fieldMappings) {
            $return['fields'] = $metadata->fieldMappings;
        }
        $assoc = $this->getAssociationNames()? : array();
        foreach ($assoc AS $a) {
            $return['childs'][$a]['fields'] = $this->getMetadata('Entity\\' . ucfirst($a))->fieldMappings;
        }
        $data = (isset($params['id']) && $params['id']) ? $this->get($params['id']) : null;
        $return['data'] = isset($data[strtolower($entity)]) && isset($data[strtolower($entity)][0]) ? $data[strtolower($entity)][0] : null;
        return $return;
    }

    public function delete($id) {
        $entity = $this->entity->find($id);
        if ($entity) {
            $this->_em->remove($entity);
            $this->_em->flush();
            return true;
        } else {
            return false;
        }
    }

    public function edit(array $params) {
        if (!isset($params['id'])) {
            ErrorModel::addError(array('code' => 'need_id_for_this_operation', 'message' => 'Need id for this operation'));
            return;
        }
        $entity = $this->entity->find($params['id']);
        if (!$entity) {
            ErrorModel::addError(array('code' => 'no_register_with_this_id', 'message' => 'No register with this ID: %1$s'), array($params['id']));
            return;
        }
        try {
            $insert = $this->setData($entity, $params);
            $this->_em->persist($insert);
            $this->_em->flush();
            return $this->get($insert->getId());
        } catch (Exception $e) {
            ErrorModel::addError(array('code' => $e->getCode(), 'message' => 'Error on edit this data'));
            ErrorModel::addError(array('code' => $e->getCode(), 'message' => $e->getMessage()));
            $this->_em->rollback();
        }
    }

    public function registerLog() {
        if ($this->config['LogChanges']) {
            echo 'x';
            die();
        }
    }

    public function insert(array $params) {
        try {
            $class = new $this->entity_name;
            $entity = $this->setData($class, $params);
            $this->_em->persist($entity);
            $this->_em->flush();
            return $this->get($entity->getId());
        } catch (Exception $e) {
            ErrorModel::addError(array('code' => 'no_insert_this_data', 'message' => 'Not insert this data'));
            ErrorModel::addError(array('code' => $e->getCode(), 'message' => $e->getMessage()));
            $this->_em->rollback();
        }
    }

    public function setData($entity, $params) {
        $field_names = $this->getFieldNames()? : array();
        foreach ($field_names as $field) {
            if ($field != 'id' && isset($params[$field])) {
                $f = 'set' . ucfirst($field);
                $entity->$f($params[$field]);
            }
        }

        $field_a_names = $this->getAssociationNames()? : array();
        foreach ($field_a_names as $field_a) {
            if (isset($params[$field_a . '_id'])) {
                $f_a = ucfirst($field_a);
                $object = $this->_em->getRepository('Entity\\' . $f_a)->find($params[$field_a . '_id']);
                $f_s = 'set' . $f_a;
                $entity->$f_s($object);
            }
        }
        return $entity;
    }

    public function getTotalResults() {
        return $this->rows;
    }

    public function getAssociationNames() {
        return $this->_em->getClassMetadata($this->entity_name)->getAssociationNames();
    }

    public function getFieldNames() {
        return $this->_em->getClassMetadata($this->entity_name)->getFieldNames();
    }

    protected function getChilds(\Doctrine\ORM\QueryBuilder &$qb, $entity_name, $join_alias) {

        if ($this->max_deep && $this->current_deep < $this->max_deep) {
            $childs = $this->_em->getClassMetadata($entity_name)->getAssociationMappings();
            foreach ($childs as $key => $child) {
                if ($child['targetEntity'] && !in_array($child['targetEntity'], $this->join)) {
                    $this->current_deep ++;
                    $this->join[] = $child['targetEntity'];
                    $j = $this->generateAlias();
                    $table = $this->getEntityKey($child['targetEntity']);
                    $this->alias[] = $j;
                    $qb->select($this->alias);
                    $qb->leftJoin($join_alias . '.' . $table, $j);
                    $table_child = $this->_em->getClassMetadata('Entity\\' . ucfirst($table))->getAssociationMappings();
                    foreach ($table_child as $k => $p) {
                        $this->getChilds($qb, 'Entity\\' . ucfirst($table), $j);
                    }
                }
            }
        }
    }

    protected function generateAlias($lenght = 10) {
        do {
            $alias = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $lenght);
        } while (in_array($alias, $this->alias));
        return $alias;
    }

    public function getWithParent($id, $entity_parent, $page = 1, $limit = 100) {

        $data = [];
        $this->children_entity_name = $this->entity_name;
        $this->entity_name = $entity_parent;
        $alias = $this->generateAlias();
        $alias_parent = $this->generateAlias();
        $qb = $this->entity->createQueryBuilder($alias)->select($alias);
        $this->join[] = $this->children_entity_name;
        $this->alias[] = $alias;
        $this->alias[] = $alias_parent;
        $qb->select(array($alias, $alias_parent));
        $qb->leftJoin($alias . '.' . strtolower($this->entity_name), $alias_parent);
        $this->getChilds($qb, $this->children_entity_name, $alias);
        $qb->where($alias_parent . '.id=' . $id);
        $query = $qb->setFirstResult($limit * ($page - 1))->setMaxResults($limit)->getQuery();
        $paginator = new Paginator($query);
        $data[$this->getEntityKey($this->children_entity_name)] = $query->getArrayResult();
        $this->rows = $paginator->count();
        return $data;
    }

    protected function getEntityKey($entity) {
        return strtolower(str_replace('Entity\\', '', $entity));
    }

    public function get($id = null, $page = 1, $limit = 100) {
        $data = [];
        $alias = $this->generateAlias();
        $qb = $this->entity->createQueryBuilder($alias)->select($alias);
        $this->join[] = $this->entity_name;
        $this->alias[] = $alias;
        if ($id) {
            $qb->where($alias . '.id=' . $id);
        }
        $this->getChilds($qb, $this->entity_name, $alias);
        $query = $qb->getQuery()->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        $data[$this->getEntityKey($this->entity_name)] = $query->getArrayResult();
        $paginator = new Paginator($query);
        $this->rows = $paginator->count();
        return $data;
    }

    public function toArray($data, $entity_name = null) {
        $hydrator = new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($this->_em, $entity_name? : $this->entity_name);
        return $hydrator->extract($data);
    }

    public function getSm() {
        return $this->_sm;
    }

    public function setSm($sm) {
        $this->_sm = $sm;
        return $this;
    }

    public function getEntityName() {
        return $this->entity_name;
    }

    public function setEntityName($entity_name) {
        $this->entity_name = $entity_name;
        return $this;
    }

}
