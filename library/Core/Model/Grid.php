<?php

/** 
 * @author developer
 * 
 * 
 */
class Core_Model_Grid extends Core_Model_Service
{

    public static $_sort = array();

    public static $_filter = array();

    public static $_group = array();

    protected static $_joins = array();
    


    protected static $_dispalyColumns = array();



    

    public static function getDisplayFields ()
    {
        foreach (static::$_dispalyColumns as $key => $column) {
            if (isset($column['entity'])) {
                $values = static::getKValues($column['entity']);
                static::$_dispalyColumns[$key]['values'] = json_encode($values);
            }
        }
        return static::$_dispalyColumns;
    }

    public static function parseResults ($results)
    {
        $array = array();
        foreach ($results as $result) {
            $temp = array();
            
            foreach (static::getDisplayFields() as $value) {
                list ($entityKey, $entityColumnName) = explode('_', 
                $value['key']);
                
                $array1 = array_reverse(static::getEntitiesArray($entityKey));
                
                $object = $result;
                foreach ($array1 as $value2) {
                    $object = $object->$value2;
                    if (! $object) {
                        $object = null;
                        break;
                    }
                }
                
                if ($object) {
                    
                    if ($value['type'] == 'date' &&
                     is_a($object->$entityColumnName, 'DateTime')) {
                        $temp[$value['key']] = $object->$entityColumnName->format(
                        
                        'd/m/Y');
                    
                    } else {
                        
                        if ($object->$entityColumnName!==null) {
                            $temp[$value['key']] = $object->$entityColumnName;
                        }
                    
                    }
                }
            
            }
            
            $array[] = $temp;
        }
        
        return $array;
    }

    public static function getEntitiesArray ($e = 'u')
    {
        $array = array();
        while (1) {
            if ($e == 'u') {
                break;
            }
            
            $key = static::$_joins[$e];
            
            list ($e, $value) = explode('_', $key);
            $array[] = $value;
        
        }
        
        return $array;
    
    }

    public static function getResults ($offset = 0, $limit = 30, $sort = array(), 
    $filter = array(), $group = array())
    {
        static::$_filter = $filter;
        static::$_sort = $sort;
        static::$_group = $group;
        return parent::getResults($offset, $limit);
    }

    public static function getWhere ($frontsql = 'select u from ')
    {
        
        $em = static::getDoctrine();
        $request = static::getRequest();
        $dql = $frontsql . ' ' . static::$_model . ' u ';
        foreach (static::$_joins as $key => $value) {
            $value = str_replace('_', '.', $value);
            $dql .= ' left join ' . $value . ' ' . $key;
        }
        
        $where = static::getFilterQuery();
        $defaultQ = static::defaultQuery();
        
            if (trim($where)) {
                $dql .= ' ' . $where ;
            } 
        
        $dql .= static::getSortString();
        
     

        $query = $em->createQuery($dql);
        
        return $query;
    }

    public static function defaultQuery ()
    {
        
    }

    public static function getSortString ()
    {
        $temp = array();
        
        foreach (static::$_sort as $value) {
            $key = str_replace('_', '.', $value['field']);
            $dir = $value['dir'];
            $temp[] = $key . ' ' . $dir;
        
        }
        if (count($temp)) {
            return ' order by ' . implode(' , ', $temp);
        }
        
        return '';
    }

    public static function getGroupString ()
    {
        $temp = array();
        
        foreach (static::$_group as $value) {
            $key = str_replace('_', '.', $value['field']);
            //$dir = $value['dir'];
            $temp[] = $key . ' ';
        
        }
        if (count($temp)) {
            return ' group by ' . implode(' , ', $temp);
        }
        
        return '';
    }

    public static function getFilterQuery ()
    {
        
        $top = array();
        
        $filter = static::$_filter;
        
        if ($filter != 'null' && count($filter)) {
            
            foreach ($filter['filters'] as $filter1) {
                
                if (isset($filter1['filters']) && is_array($filter1['filters'])) {
                    $top1 = array(); //filter['filters'][0]
                    foreach ($filter1['filters'] as $filter2) {
                        //filter['filters'][0]['filters'][0]
                        $key = str_replace('_', '.', 
                        $filter2['field']);
                        $top1[] = ' ' . $key . static::getOperatorPlusValue(
                        $filter2['operator'], $filter2);
                    }
                    
                    if (! isset($filter1['logic'])) {
                        $filter1['logic'] = ' and ';
                    }
                    
                    $top[] = ' ( ' . implode(' ' . $filter1['logic'] . ' ', 
                    $top1) . ' ) ';
                } else {
                    
                    $key = str_replace('_', '.', $filter1['field']);
                    $top[] = ' ' . $key . static::getOperatorPlusValue(
                    $filter1['operator'], $filter1);
                }
            
            }
            if (! isset($filter['logic'])) {
                $filter['logic'] = ' and ';
            }
            
            return ' where  ' . implode(' ' . $filter['logic'] . ' ', $top) .
             '  ';
        }
        return ' ';
    
    }

    public static function getOperatorPlusValue ($op, $filter)
    {
        $value = $filter['value'];
        $key = $filter['field'];
        
        $temp = array("eq", "==", "isequalto", "equals", "equalto", "equal");
        if (in_array($op, $temp)) {
            return ' = ' . "'" . $value . "'";
        }
        $temp = array(
            "neq", 
            "!=", 
            "isnotequalto", 
            "notequals", 
            "notequalto", 
            "notequal", 
            "ne");
        if (in_array($op, $temp)) {
            return ' != ' . "'" . $value . "'";
        }
        
        $temp = array("lt", "<", "islessthan", "lessthan", "less");
        if (in_array($op, $temp)) {
            
            return ' < ' . "'" . $value . "'";
        }
        
        $temp = array("lte", "<=", "islessthanorequalto", "lessthanequal", "le");
        if (in_array($op, $temp)) {
            if ($value && isset(static::$_dispalyColumns[$key]['type']) &&
             (static::$_dispalyColumns[$key]['type'] == 'date')) {
                $value = date('Y-m-d', strtotime('+1 day', strtotime($value)));
            }
            return ' <= ' . "'" . $value . "'";
        }
        
        $temp = array("gt", ">", "isgreaterthan", "greaterthan", "greater");
        if (in_array($op, $temp)) {
            
            return ' > ' . "'" . $value . "'";
        }
        
        $temp = array(
            "gte", 
            ">=", 
            "isgreaterthanorequalto", 
            "greaterthanequal", 
            "ge");
        if (in_array($op, $temp)) {
            
            return ' >= ' . "'" . $value . "'";
        }
        
        $temp = array("startswith");
        
        if (in_array($op, $temp)) {
            return ' like ' . "'" . $value . "%'";
        }
        
        $temp = array("endswith");
        
        if (in_array($op, $temp)) {
            return ' like ' . "'%" . $value . "'";
        }
        
        $temp = array("contains", "substringof");
        
        if (in_array($op, $temp)) {
            return ' like ' . "'%" . $value . "%'";
        }
        
        $temp = array("doesnotcontain");
        
        if (in_array($op, $temp)) {
            return ' not like ' . "'%" . $value . "%'";
        }
        return '';
    
    }

}
