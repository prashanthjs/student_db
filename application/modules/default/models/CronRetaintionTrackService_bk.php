<?php


/*
 * $totalamount = $amount
* 1.do sRetaintion- retaintion.
* 2. if(sRetaintion < 0)
	* 		add -sRetiantion to SurveyRetaintionAmount
* $totalamount = $amount + sRetention
*   if($totalAmount < 0){
*   add totalAmount to SurveyRe
*   totalAmount = 0;
*   }
*
*    else{
*     if(sRetaintion >= 700)
	*     leave it
*     else{
*     10% of amount and add it to the retentionTrack
*    }
*
*
*
*/

class Default_Model_CronRetaintionTrackService extends Core_Model_Service {
	
	protected static $_model = "Entities\\Entity\\SurveyorRetaintionTrack";
	protected static $_agencies = array ();
	protected static $_week = 1;
	protected static $_year = 2012;
	
	
	
	public static function processTotalAmount($week, $year) {
		static::$_week = $week;
		static :: $_year = $year;
		$em = static::getDoctrine ();
		$dql = 'select u from Entities\\Entity\\Surveyor u join u.user u1 order by u.id asc';
		$query = $em->createQuery ( $dql );
		$results = $query->getResult ();
		foreach ( $results as $result ) {
			static::processSurveyorAmount ( $result->user );
		}
	}
	
	public static function processSurveyorAmount($surveyor) {
		
		$em = static::getDoctrine ();
		$dql = ' select u from Entities\\Entity\\SurveyorAmountPaid u join u.user u1 where u1.id= '.$surveyor->id . ' and u.week ='.static :: $_week . ' and u.year ='.static ::$_year;

		$query = $em->createQuery($dql);
		$results = $query ->getResult();
		if($results){
			return false;
		}
		
		$retaintionPlanAmount = static::getPlanRetaintionAmount ( $surveyor );
		$retaintionPlanPercentage = static::getPlanRetaintionPercentage ( $surveyor );
		
		$retention = static::caluclateSurveyorRetaintion ( $surveyor );
		$sRetaintion = static::caluclateSurveyorRetaintionTrack ( $surveyor );
		$amount = static::caluclateSurveyorInvoiceAmount ( $surveyor );
		
		$amountToBePaid = $amount;
		$amountRetained = $sRetaintion - $retention;
		$finalRetaintion = 0;
		
		if ($amountRetained < 0) {
			$amountToBePaid = $amount - abs ( $amountRetained );
			
			if ($amountToBePaid < 0) {
				$model = new Entities\Entity\SurveyorRetaintionTrack ();
				$model->user = $surveyor;
				$model->amount =  $amountToBePaid ;
				$em->persist ( $model );
				$em->flush ();
			
			}
			$model = new Entities\Entity\SurveyorRetaintionTrack ();
			$model->user = $surveyor;
			$model->amount =  -$sRetaintion;
			$em->persist ( $model );
			$em->flush ();
			$finalRetaintion = $amountRetained;
			
		} else {
			if ($amountRetained < $retaintionPlanAmount) {
				
				$finalRetaintion = ($retaintionPlanPercentage * $amountToBePaid / 100);
				$amountToBePaid = $amountToBePaid - $finalRetaintion;
				$model = new Entities\Entity\SurveyorRetaintionTrack ();
				$model->user = $surveyor;
				$model->amount = $finalRetaintion;
				$em->persist ( $model );
				$em->flush ();
			}
		
		}
		
		$model1 = new Entities\Entity\SurveyorAmountPaid ();
		$model1->basic = 0;
		$model1->fines = $retention ? $retention : 0;
		$model1->amountToPaid = $amountToBePaid;
		$model1->surveyorRetaintion = $sRetaintion?$sRetaintion:0;
		$model1->currentRetaintion = $finalRetaintion?$finalRetaintion: 0;
		$model1->actualAmount = $amount ? $amount : 0;
		$model1->user = $surveyor;
		$model1->week = static::$_week;
		$model1->year = static::$_year;
		$em->persist ( $model1 );
		$em->flush ();
	}
	
	public static function getPlanRetaintionAmount($surveyor) {
		$retaintionAmount = 0;
		$plan = static::getCurrentPlan ( $surveyor, 'surveyor' );
		
		if ($plan && $plan->pricePlan->redemption)
			$retaintionAmount = $plan->pricePlan->redemption->redemptionAmount;
		return $retaintionAmount;
	}
	
	public static function getPlanRetaintionPercentage($surveyor) {
		$retaintionAmount = 0;
		$plan = static::getCurrentPlan ( $surveyor, 'surveyor' );
		if ($plan && $plan->pricePlan->redemption)
			$retaintionAmount = $plan->pricePlan->redemption->redemptionPercentage;
		return $retaintionAmount;
	}
	
	public static function getCurrentPlan($userOrAgency, $type = 'agency') {
		$date = date ( 'Y-m-d' );
		$plan = Price_Model_PlanService::getDetailedPlanResult ( $userOrAgency->id, $date, $type ); //check it
		return $plan;
	}
	
	public static function caluclateSurveyorRetaintion($surveyor) {
		$startDate = date ( 'Y-m-d' );
		$endDate = date ( 'Y-m-d', strtotime ( '+1 day' ) );
		
		$em = static::getDoctrine ();
		$dql = 'select sum(u.amount) from Entities\\Entity\\SurveyorRedemption u join u.user u1 where u1.id = ' . $surveyor->id . ' and u.date >= \'' . $startDate . "' and u.date < '" . $endDate . "'";
	
		$query = $em->createQuery ( $dql );
		return $query->getSingleScalarResult ();
	
	}
	
	public static function caluclateSurveyorRetaintionTrack($surveyor) {
		$em = static::getDoctrine ();
		$dql = 'select sum(u.amount) from Entities\\Entity\\SurveyorRetaintionTrack u join u.user u1 where u1.id =' . $surveyor->id;
		$query = $em->createQuery ( $dql );
		return $query->getSingleScalarResult ();
	}
	
	public static function caluclateSurveyorInvoiceAmount($surveyor) {
		$startDate = date ( 'Y-m-d' );
		$endDate = date ( 'Y-m-d', strtotime ( '+1 day' ) );
		
		$em = static::getDoctrine ();
		$dql = 'select sum(u.amount) from Entities\\Entity\\SurveyorInvoice u join u.user u1 where u1.id = ' . $surveyor->id . ' and u.paymentDate >=\'' . $startDate . "' and u.paymentDate < '" . $endDate . "'";
		$query = $em->createQuery ( $dql );
		return $query->getSingleScalarResult ();
	}

}
