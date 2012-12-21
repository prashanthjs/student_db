<?php

/** 
 * @author developer
 * 
 * 
 */
class Core_Model_Service
{

    public static $em;

    protected static $_model = "Entities\\Entity\\User";

    protected static $_columns = array('username', 'password');

    protected static $_recordNotFoundMessage = 'No Record Found';

    public static function getResults ($offset = 0, $limit = 30)
    {
        $cQuery = static::getCountDql();
        $query = static::getDql();
        $results = static::pagination($cQuery, $query, $offset, $limit);
        return $results;
    }

    public static function getCountDql ()
    {
        
        $cQuery = static::getWhere(' select count(u) from ');
        return $cQuery;
    }

    public static function getDql ()
    {
        $query = static::getWhere(' select u from ');
        return $query;
    }

    public static function getWhere ($frontsql = 'select u from ')
    {
        
        $em = static::getDoctrine();
        $request = static::getRequest();
        $dql = $frontsql . ' ' . static::$_model . ' u';
        $dql .= ' order by u.id desc';
        $query = $em->createQuery($dql);
        
        return $query;
    }

    public static function getRequest ()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        return $request;
    }

    public static function getRecord ($id)
    {
        $em = static::getDoctrine();
        
        $model = $em->find(static::$_model, $id);
        
        if (! $model) {
            throw new Exception(static::$_recordNotFoundMessage);
        }
        return $model;
    }

    public static function getRecordArray ($id)
    {
        
        $model = static::getRecord($id);
        
        $values = array();
        
        foreach (static::$_columns as $key => $column) {
            
            if (is_array($column)) {
                
                foreach ($column as $columnValue) {
                    if (is_object($model->$key->$columnValue)) {
                        $values[$columnValue] = $model->$key->$columnValue->id;
                    
                    } else {
                        
                        $values[$columnValue] = $model->$key->$columnValue;
                    }
                }
            } 

            elseif (is_object($model->$column)) {
                $values[$column] = $model->$column->id;
            } else {
                if (is_int($key)) {
                    $values[$column] = $model->$column;
                } else {
                    $values[$key] = $model->$column;
                }
            }
        
        }
        
        return $values;
    
    }

    public static function save ($params)
    {
        
        $em = static::getDoctrine();
        if ($params['id'] != "") {
            
            $model = static::getRecord($params['id']);
        
        } else {
            $model = new static::$_model();
        }
        
        foreach (static::$_columns as $key => $column) {
            
            if ($column != 'id' && ! is_array($column) &&
             isset($params[$column])) {
                if (is_int($key))
                    $model->$column = $params[$column];
                else
                    $model->$column = $params[$key];
            }
            
            if (is_array($column)) {
                if (! $model->$key) {
                    $modelName = "Entities\\Entity\\" . ucfirst($key);
                    $temp = new $modelName();
                } else {
                    $temp = $model->$key;
                
                }
                
                foreach ($column as $columnValue) {
                    if (isset($params[$columnValue]))
                        $temp->$columnValue = $params[$columnValue];
                }
                $em->persist($temp);
                $em->flush();
                $model->$key = $temp;
            }
        
        }
        
        $em->persist($model);
        $em->flush();
        
        static::postSave($model, $params);
        
        return $model;
    
    }

    public static function postSave ($model, $params)
    {

    }

    public static function delete ($id)
    {
        $em = static::getDoctrine();
        
        $q = $em->createQuery(
        'delete from ' . static::$_model . ' u where u.id in (' . $id . ')');
        
        $numDeleted = $q->execute();
        return $numDeleted;
    
    }

    public static function enable ($id)
    {
        $em = static::getDoctrine();
        
        $dql = 'UPDATE ' . static::$_model .
         ' u  SET u.status = 1   where u.id in (' . $id . ')';
        $q = $em->createQuery($dql);
        
        $numDeleted = $q->execute();
        return $numDeleted;
    
    }

    public static function disable ($id)
    {
        $em = static::getDoctrine();
        
        $dql = 'UPDATE ' . static::$_model .
         ' u  SET u.status = 2   where u.id in (' . $id . ')';
        $q = $em->createQuery($dql);
        
        $numDeleted = $q->execute();
        return $numDeleted;
    
    }

    public static function getDoctrine ()
    {
        if (! isset(static::$em)) {
            static::$em = Zend_Registry::get('doctrine')->getEntityManager();
        }
        return static::$em;
    }

    public static function pagination ($cQuery, $query, $offset = 1, $limit = 30)
    {
        
        $resultArray = array();
        $count = $cQuery->getSingleScalarResult();
        $resultArray["total"] = $count;
        
        if (($offset) * $limit > $count) {
            $request = static::getRequest();
            $offset = ceil($count / $limit);
            $request->setParam('page', $offset);
        
        }
        
        if ($count) {
            $query->setMaxResults($limit);
            $query->setFirstResult(($offset - 1) * $limit);
            $results = $query->getResult();
            $resultArray["results"] = $results;
        }
        
        return $resultArray;
    }

    public static function getConfig ()
    {
        return Zend_Registry::get('config');
    }

    public static function extractValue ($value)
    {
        
        if ($value && preg_match('/\(([^\)]+)\)/', $value, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public static function getKValues ($entity)
    {
        
        $em = static::getDoctrine();
        
        $dql = ' select u from ' . $entity . ' u ';
        $query = $em->createQuery($dql);
        $results = $query->getResult();
        $array = array();
        foreach ($results as $result) {
            $array[] = array('value' => $result->id, 'text' => $result->name);
        }
        return $array;
    
    }

}
