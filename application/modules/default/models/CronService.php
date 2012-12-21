<?php

class Default_Model_CronService extends Core_Model_Service
{

    protected static $_model = "Entities\\Entity\\Survey";

    protected static $_agencies = array();

    protected static $startDate = '';

    protected static $endDate = '';

    public static function invoice ($startDate, $endDate)
    {
        static::$startDate = $startDate;
        static::$endDate = $endDate;
        
        //$surveys = static::getSurveys ( $startDate, $endDate );
        

        $gSurveys = static::getGSurveys($startDate, $endDate);
        $cSurveys = static::getCSurveys($startDate, $endDate);
        
        static::processGPayments($gSurveys);
        static::processCPayments($cSurveys);
    }

    public static function getGSurveys ($startDate = '', $endDate = '')
    {
        $em = static::getDoctrine();
        
        $dql = 'select u from Entities\\Entity\\PaymentPaid u  where   u.effectiveDate >=\'' .
         $startDate . "' and u.effectiveDate < '" . $endDate . "'";
        $q = $em->createQuery($dql);
        $results = $q->getResult();
        // 	    \Doctrine\Common\Util\Debug::dump($results);
        // 	    exit;
        return $results;
    }

    public static function getCSurveys ($startDate = '', $endDate = '')
    {
        $em = static::getDoctrine();
        
        $dql = 'select u from Entities\\Entity\\PaymentCancellation u  where   u.effectiveDate >=\'' .
         $startDate . "' and u.effectiveDate < '" . $endDate . "'";
        $q = $em->createQuery($dql);
        $results = $q->getResult();
      
        return $results;
    }

    // 	public static function getSurveys($startDate = '', $endDate = '') {
    // 		$em = static::getDoctrine ();
    // 		$status = array (Core_Constants::SURVEY_GOOD_TO_GO, Core_Constants::SURVEY_CANCELLED );
    // 		$dql = 'select u from ' . static::$_model . ' u join u.surveyStatus s where s.id in (' . implode ( ",", $status ) . ') and  u.paymentDate >=\'' . $startDate . "' and u.paymentDate < '" . $endDate . "'";
    // 		$q = $em->createQuery ( $dql );
    // 		$results = $q->getResult ();
    // 		return $results;
    // 	}
    

    // 	public static function processPayments($surveys) {
    // 		foreach ( $surveys as $survey ) {
    // 			$surveyor = $survey->user;
    // 			static::processPaymentForSurveyor ( $survey );
    // 			static::$_agencies = array ();
    // 			static::getParentAgencies ( $surveyor->agency );
    // 			static::processPaymentForAgencies ( $survey, static::$_agencies );
    

    // 		}
    // 	}
    

    public static function processGPayments ($gsurveys)
    {
        foreach ($gsurveys as $gsurvey) {
            $survey = $gsurvey->survey;
            $surveyor = $survey->user;
            static::processPaymentForSurveyor($survey);
            static::$_agencies = array();
            static::getParentAgencies($surveyor->agency);
            static::processPaymentForAgencies($survey, static::$_agencies);
        
        }
    }

    public static function processCPayments ($csurveys)
    {
        foreach ($csurveys as $csurvey) {
            $survey = $csurvey->survey;
            $fee = $csurvey->fee;
            $surveyor = $survey->user;
            static::processPaymentForSurveyor($survey, $fee);
            static::$_agencies = array();
            static::getParentAgencies($surveyor->agency);
            static::processPaymentForAgencies($survey, static::$_agencies, $fee);
        
        }
    }

    public static function processPaymentForSurveyor ($survey, $fee = '')
    {
        $em = static::getDoctrine();
        $surveyor = $survey->user;
        if ($survey->surveyStatus->id == Core_Constants::SURVEY_GOOD_TO_GO) {
            static::processPaymentForSurveyorGoodToGo($survey, $surveyor);
        }
        if ($survey->surveyStatus->id == Core_Constants::SURVEY_CANCELLED) {
            static::processPaymentForSurveyorCancellation($survey, $surveyor, $fee);
        }
    
    }

    public static function processPaymentForSurveyorGoodToGo ($survey, $surveyor)
    {
        $em = static::getDoctrine();
        $dql = ' select u from Entities\\Entity\\SurveyorInvoice u join u.user u1 join u.survey s where u1.id =' .
         $surveyor->id . ' and s.id =' . $survey->id;
        
        $q = $em->createQuery($dql);
        $result = $q->getResult();
        if (! $result) {
            
            $price = static::getPlanComission($survey, $surveyor, 'surveyor');
            // 			echo ' survey - '. $survey->id;
            // 			echo '<br>';
            

            $model = new Entities\Entity\SurveyorInvoice();
            $model->survey = $survey;
            $model->user = $surveyor;
            $model->amount = $price;
            $em->persist($model);
            $em->flush();
        }
    }

    public static function processPaymentForSurveyorCancellation ($survey, 
    $surveyor, $fee='')
    {
//         $em = static::getDoctrine();
//         $dql = ' select u from Entities\\Entity\\SurveyorInvoice u join u.user u1 join u.survey s where u1.id =' .
//          $surveyor->id . ' and s.id =' . $survey->id;
//         $q = $em->createQuery($dql);
//         $result = $q->getResult();
//         if ($result) {
            
//             $dql1 = ' select u from Entities\\Entity\\SurveyorRedemption u join u.user u1 join u.survey s where u1.id =' .
//              $surveyor->id . ' and s.id =' . $survey->id;
//             $q1 = $em->createQuery($dql1);
//             $result1 = $q1->getResult();
//             if (! $result1) {
                
//                 $model = new Entities\Entity\SurveyorRedemption();
//                 $model->survey = $survey;
//                 $model->user = $surveyor;
//                 $model->amount = array_pop($result)->amount;
//                 $em->persist($model);
//                 $em->flush();
//             }
//         }

        $em = static::getDoctrine();
        $dql1 = ' select u from Entities\\Entity\\SurveyorRedemption u join u.user u1 join u.survey s where u1.id ='.
         $surveyor->id . ' and s.id =' . $survey->id;
        $q1 = $em->createQuery($dql1);
        $result1 = $q1->getResult();
        if (! $result1) {
            
            $model = new Entities\Entity\SurveyorRedemption();
            $model->survey = $survey;
            $model->user = $surveyor;
            $model->amount = $fee?$fee:0;
            $em->persist($model);
            $em->flush();
        }

        
    }

    public static function processPaymentForAgencies ($survey, $agencies, $fee='')
    {
        foreach ($agencies as $agency) {
            if ($survey->surveyStatus->id == Core_Constants::SURVEY_GOOD_TO_GO) {
                static::processPaymentForAgencyGoodToGo($survey, $agency);
            }
            if ($survey->surveyStatus->id == Core_Constants::SURVEY_CANCELLED) {
                static::processPaymentForAgencyCancellation($survey, $agency, $fee);
            }
        
        }
    }

    public static function processPaymentForAgencyGoodToGo ($survey, $agency)
    {
        $em = static::getDoctrine();
        $dql = ' select u from Entities\\Entity\\AgencyInvoice u join u.agency u1 join u.survey s where u1.id =' .
         $agency->id . ' and s.id =' . $survey->id;
        $q = $em->createQuery($dql);
        $result = $q->getResult();
        if (! $result) {
            $price = static::getPlanComission($survey, $agency, 'agency');
            $model = new Entities\Entity\AgencyInvoice();
            $model->survey = $survey;
            $model->agency = $agency;
            $model->amount = $price;
            $em->persist($model);
            $em->flush();
        }
    }

    public static function processPaymentForAgencyCancellation ($survey, $agency, $fee='')
    {
        $em = static::getDoctrine();
//         $dql = ' select u from Entities\\Entity\\AgencyInvoice u join u.agency u1 join u.survey s where u1.id =' .
//          $agency->id . ' and s.id =' . $survey->id;
//         $q = $em->createQuery($dql);
//         $result = $q->getResult();
//         if ($result) {
            
//             $dql1 = ' select u from Entities\\Entity\\AgencyRedemption u join u.agency u1 join u.survey s where u1.id =' .
//              $agency->id . ' and s.id =' . $survey->id;
//             $q1 = $em->createQuery($dql1);
//             $result1 = $q1->getResult();
//             if (! $result1) {
                
//                 $model = new Entities\Entity\AgencyRedemption();
//                 $model->survey = $survey;
//                 $model->agency = $agency;
//                 $model->amount = array_pop($result)->amount;
//                 $em->persist($model);
//                 $em->flush();
//             }
//         }

        $dql1 = ' select u from Entities\\Entity\\AgencyRedemption u join u.agency u1 join u.survey s where u1.id ='.
         $agency->id . ' and s.id =' . $survey->id;
        $q1 = $em->createQuery($dql1);
        $result1 = $q1->getResult();
        if (! $result1) {
            
            $model = new Entities\Entity\AgencyRedemption();
            $model->survey = $survey;
            $model->agency = $agency;
            $model->amount = $fee?$fee:0;
            $em->persist($model);
            $em->flush();
        }
    }

    public static function getParentAgencies ($parent = null)
    {
        if (! $parent) {
            return false;
        }
        static::$_agencies[$parent->id] = $parent;
        return static::getParentAgencies($parent->parent);
    }

    public static function getPlanComission ($survey, $userOrAgency, 
    $type = 'agency')
    {
        $price = 0;
        $date1 = DateTime::createFromFormat('d/m/Y', $survey->submittedDate)->add(
        new DateInterval('P1D'))->format('Y-m-d');
        $date = DateTime::createFromFormat('d/m/Y', $survey->submittedDate)->format(
        'Y-m-d');
        $plan = Price_Model_PlanService::getDetailedPlanResult(
        $userOrAgency->id, $date1, $type); //check it
        if ($plan && $plan->pricePlan) {
            $planName = $plan->pricePlan->planName;
            $pricePlan = Price_Model_PlanService::getDetailedPriceResult(
            $plan->pricePlan->id, $survey->priority->id, $date, 
            $survey->installation->id);
            if ($pricePlan) {
                $price = $pricePlan->price;
            }
        }
        return $price;
    }

}
