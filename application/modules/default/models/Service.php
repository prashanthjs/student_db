<?php

class Default_Model_Service extends Core_Model_Service
{

    protected static $_model = "Entities\\Entity\\Survey";

    protected static $_columns = array('user', 'address1', 'address2', 'dos');

    /* count function */
    public static function status ($status, $startDate, $endDate, $id, $type)
    {
        return static::query($status, null, $startDate, $endDate, $id, $type);
    }

    public static function priority ($priority, $startDate, $endDate, $id, $type)
    {
        return static::query(null, $priority, $startDate, $endDate, $id, $type);
    
    }

    public static function query ($status, $priority, $startDate, $endDate, $id, 
    $type)
    {
        $em = static::getDoctrine();
        if ($type == 'agency') {
            if ($id) {
                $agencies = implode(',', 
                Api_Model_Service::getAgencies($id, true));
            
            } else {
                $agencies = implode(',', 
                Api_Model_Service::getAgencies(null, true));
            }
            
            $dql = 'select sum(i.unit) from ' . static::$_model .
             ' s inner join s.user u inner join u.agency a inner join s.surveyStatus s1 inner join s.priority p inner join s.installation i where a.id in (' .
             $agencies . ') ';
        
        } else {
            
            $dql = 'select sum(i.unit) from ' . static::$_model .
             ' s inner join s.user u inner join s.surveyStatus s1 inner join s.priority p inner join s.installation i where u.id  = ' .
             $id . ' ';
        }
        
        if ($status) {
            $dql .= ' and s1.id = ' . $status . ' ';
        }
        if ($priority) {
            $dql .= ' and p.id = ' . $priority . ' ';
        }
        if ($startDate) {
            $dql .= " and s.submittedDate >='" . $startDate . "' ";
        }
        if ($endDate) {
            
            $dql .= " and s.submittedDate <'" . $endDate . "' ";
        }
        
        $query = $em->createQuery($dql);
        $result = $query->getSingleScalarResult();
        if (! $result) {
            return 0;
        }
        return $result;
    
     //return $query->getSingleScalarResult ();
    }

    // 	public static function caluclateCost($id, $type = 'agency',$priorityId = null) {
    // 		$em = static::getDoctrine ();
    // 		if ($type == 'agency') {
    // 			if ($id) {
    // 				$agencies = implode ( ',', Api_Model_Service::getAgencies ( $id, true ) );
    // 			} else {
    // 				$agencies = implode ( ',', Api_Model_Service::getAgencies ( null, true ) );
    // 			}
    // 			$dql = 'select s from ' . static::$_model . ' s inner join s.user u inner join u.agency a inner join s.surveyStatus s1 inner join s.priority p where a.id in (' . $agencies . ') ';
    // 		} else {
    

    // 			$dql = 'select s from ' . static::$_model . ' s inner join s.user u inner join s.surveyStatus s1 inner join s.priority p where u.id  = ' . $id . ' ';
    // 		}
    // 		if($priorityId){
    // 			$dql .= ' and p.id = ' . $priorityId . ' ';
    // 		}
    

    // 		$dql .= ' and s1.id = ' . Core_Constants::SURVEY_GOOD_TO_GO . ' ';
    // 		$query = $em->createQuery ( $dql );
    // 		$results = $query->getResult ();
    // 		$priceArray = array ();
    // 		$planArray = array ();
    // 		foreach ( $results as $result ) {
    // 			$price = 0;
    // 			$date = date ( 'Y-m-d', strtotime ( $result->submittedDate ) );
    // 			$plan = static::getDetailedPlanResult ( $id, $date );
    // 			if ($plan) {
    // 				$planArray [$result->priority->id] [$result->id] = $plan->pricePlan->planName;
    

    // 				$pricePlan = static::getDetailedPriceResult ( $plan->pricePlan->id, $result->priority->id, $date );
    // 				if ($pricePlan) {
    // 					$price = $pricePlan->price;
    // 				}
    // 				$priceArray [$result->priority->id] [$result->id] = $price;
    

    // 			}
    

    // 		}
    // // 		echo '<pre>';
    // // 		var_dump($priceArray);
    // // 		var_dump($planArray);
    

    // 		return array('price'=>$priceArray,'plan'=>$planArray);
    

    // 	}
    

    public static function getDetailedPlanResult ($id, $date, $type = 'agency')
    {
        $em = static::getDoctrine();
        if ($type == 'agency') {
            $dql = 'select u from Entities\\Entity\\AgencyPlan u join u.agency a where a.id = ' .
             $id . '   ';
        } else {
            $dql = 'select u from Entities\\Entity\\SurveyorPlan u join u.surveyor a where a.id = ' .
             $id . '   ';
        }
        $dql .= " and u.startDate <= '" . $date . "' order by u.id desc ";
        
        $query = $em->createQuery($dql);
        $query->setMaxResults(1);
        $results = $query->getResult();
        
        if ($results) {
            $result = array_pop($results);
            return $result;
        }
        return false;
    }

    public function getDetailedPriceResult ($id, $priorityId, $date)
    {
        $em = static::getDoctrine();
        $dql = 'select u from Entities\\Entity\\PriceDetail u join u.pricePlan p join u.priority pr where p.id = ' .
         $id . ' and pr.id = ' . $priorityId . ' ';
        $dql .= " and u.startDate <= '" . $date . "' order by u.id desc ";
        $query = $em->createQuery($dql);
        $query->setMaxResults(1);
        $results = $query->getResult();
        $price = 0;
        if ($results) {
            $result = array_pop($results);
            return $result;
        }
        return false;
    }

    public static function getSurveyStatus ()
    {
        $em = static::getDoctrine();
        $dql = 'select u from Entities\\Entity\\SurveyStatus u order by u.id asc';
        $query = $em->createQuery($dql);
        return $query->getResult();
    }

    public static function getPriorities ()
    {
        $em = static::getDoctrine();
        $dql = 'select u from Entities\\Entity\\Priority u order by u.id asc';
        $query = $em->createQuery($dql);
        return $query->getResult();
    }

    public static function caluclateCost ($id, $type, $var, $surveyId = null, 
    $startDate, $endDate)
    {
        $results = Price_Model_PlanService::caluclateCost($id, $type, $var, 
        $surveyId, $startDate, $endDate);
        $array = array('count' => 0, 'price' => 0);
        $price = 0;
        foreach ($results as $result) {
            $price += $result['price'];
        }
        $array['count'] = count($results);
        $array['price'] = $price;
        
        return $array;
    }

    public static function getCancellations ($id, $type)
    {
        $em = static::getDoctrine();
        if ($type == 'agency') {
            
            $dql = 'select u from  Entities\Entity\AgencyRedemption  u  ';
            if ($id) {
                $dql .= ' join u.agency a where a.id = ' . $id;
            }
        } else {
            $dql = 'select u from  Entities\Entity\SurveyorRedemption  u   join u.user u1 where u1.id = ' .
             $id;
        }
        $query = $em->createQuery($dql);
        return $query->getResult();
    }

    public static function getGoodToGos ($id, $type)
    {
        $em = static::getDoctrine();
        if ($type == 'agency') {
            $dql = 'select u from  Entities\Entity\AgencyInvoice  u ';
            if ($id) {
                $dql .= ' join u.agency a where a.id = ' . $id;
            }
        } else {
            $dql = 'select u from  Entities\Entity\SurveyorInvoice  u   join u.user u1 where u1.id = ' .
             $id;
        }
        
        $query = $em->createQuery($dql);
        
        return $query->getResult();
    }

    public static function getAmountPaid ($id, $type)
    {
        $em = static::getDoctrine();
        if ($type == 'agency') {
            $dql = 'select u from  Entities\Entity\AgencyAmountPaid  u join u.status s  ';
            if ($id) {
                $dql .= ' join u.agency a where a.id = ' . $id;
            }
        
        } else {
            $dql = 'select u from  Entities\Entity\SurveyorAmountPaid  u   join u.user u1 join u.status s where u1.id = ' .
             $id;
        }
        if (Core_Constants::isSurveyor()) {
            $dql .= ' and s.id = ' . Core_Constants::STATUS_ENABLED . ' ';
        }
        $query = $em->createQuery($dql);
        return $query->getResult();
    }

    public static function getSurveyorLogs ($id)
    {
        $em = static::getDoctrine();
        $dql = 'select u from  Entities\Entity\Log  u join u.user u1 where u1.id =  ' .
         $id . ' order by u.id desc ';
        $query = $em->createQuery($dql);
        $query->setMaxResults(20);
        return $query->getResult();
    }

    public static function getAgencyLogs ($id)
    {
        $ids = implode(", ", Api_Model_Service::getAgencies($id));
        
        $em = static::getDoctrine();
        $dql = 'select u from  Entities\Entity\Log  u join u.user u1 join u1.agency a where a.id in  ('.$ids.') ' .
          ' order by u.id desc ';
       
        $query = $em->createQuery($dql);
        $query->setMaxResults(50);
        return $query->getResult();
    
    }

}
