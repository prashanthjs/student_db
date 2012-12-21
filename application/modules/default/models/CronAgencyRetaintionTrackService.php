<?php


class Default_Model_CronAgencyRetaintionTrackService extends Core_Model_Service {
	
	protected static $_model = "Entities\\Entity\\AgencyRetaintionTrack";
	protected static $_agencies = array ();
	protected static $_week = 1;
	protected static $_year = 2012;
	
	public static function processTotalAmount($week, $year) {
		static::$_week = $week;
		static::$_year = $year;
		$em = static::getDoctrine ();
		$dql = 'select u from Entities\\Entity\\Agency u order by u.id asc';
		$query = $em->createQuery ( $dql );
		$results = $query->getResult ();
		foreach ( $results as $result ) {
			static::processAgencyAmount ( $result );
		}
	}
	
	public static function processAgencyAmount($agency) {
		
		$em = static::getDoctrine ();
		$dql = ' select u from Entities\\Entity\\AgencyAmountPaid u join u.agency u1 where u1.id= ' . $agency->id . ' and u.week =' . static::$_week . ' and u.year =' . static::$_year;
		$query = $em->createQuery ( $dql );
		$results = $query->getResult ();
		if ($results) {
			return false;
		}
		
		$retaintionPlanAmount = static::getPlanRetaintionAmount ( $agency );
		$retaintionPlanPercentage = static::getPlanRetaintionPercentage ( $agency );
		$vatPercentage = static::getVatPercentage ( $agency );
		
		$cancellations = static::caluclateAgencyRetaintion ( $agency );
		$sRetaintion = static::caluclateAgencyRetaintionTrack ( $agency );
		$amount = static::caluclateAgencyInvoiceAmount ( $agency );
		$dues = static::caluclateAgencyDues ( $agency );
		$amountToBePaid = $amount;
		$finalRetaintion = 0;
		
		$amountToBePaid = $amount - $dues - $cancellations;
		
		$retaintionAmount = 0;
		$vatAmount = 0;
		$finalRetaintion = 0;
		
		$dql = 'DELETE Entities\Entity\AgencyDues u  WHERE u.agency = ' . $agency->id;
		
		
		$query = $em->createQuery ( $dql );
		
		
		$numDeleted = $query->execute ();
		
		
		;
		if ($amountToBePaid < 0) {
			if ($sRetaintion > 0) {
				$finalRetaintion = $sRetaintion - abs ( $amountToBePaid );
				if ($finalRetaintion < 0) {
					/* make $finalRetantion it as due and insert it */
					$model = new Entities\Entity\AgencyDues ();
					$model->agency = $agency;
					$model->amount = abs ( $finalRetaintion );
					$em->persist ( $model );
					$em->flush ();
					
					/* make it as $sRetaintion -ve value */
					$model = new Entities\Entity\AgencyRetaintionTrack ();
					$model->agency = $agency;
					$model->amount = - $sRetaintion;
					$em->persist ( $model );
					$em->flush ();
					$amountToBePaid = $finalRetaintion;
				} else {
					/* add -ve amountToBePaid in retaintion  as   value */
					
					$model = new Entities\Entity\AgencyRetaintionTrack ();
					$model->agency = $agency;
					$model->amount = $amountToBePaid; //-ve value
					$em->persist ( $model );
					$em->flush ();
					$amountToBePaid = 0;
				}
			
			} else {
				/* make $amount to paid as due it as due and insert it */
				
				$model = new Entities\Entity\AgencyDues ();
				$model->agency = $agency;
				$model->amount = abs ( $amountToBePaid );
				$em->persist ( $model );
				$em->flush ();
			}
		
		} else {
			
			if ($amountToBePaid < $retaintionPlanAmount) {
				$finalRetaintion = ($amountToBePaid * $retaintionPlanPercentage / 100);
				$amountToBePaid = $amountToBePaid - $finalRetaintion;
				
				$model = new Entities\Entity\AgencyRetaintionTrack ();
				$model->agency = $agency;
				$model->amount = $finalRetaintion; //-ve value
				$em->persist ( $model );
				$em->flush ();
			}
			$vatAmount = ($amountToBePaid * $vatPercentage / 100);
			$amountToBePaid = $amountToBePaid + $vatAmount;
			
		}
		
		$model1 = new Entities\Entity\AgencyAmountPaid ();
		$model1->basic = 0;
		$model1->fines = $cancellations ? $cancellations : 0;
		$model1->amountToPaid = $amountToBePaid;
		$model1->agencyRetaintion = $sRetaintion ? $sRetaintion : 0;
		$model1->currentRetaintion = $finalRetaintion ? $finalRetaintion : 0;
		$model1->actualAmount = $amount ? $amount : 0;
		$model1->dues = $dues ? $dues : 0;
		$model1->vat = $vatAmount ? $vatAmount : 0;
		$model1->agency = $agency;
		$model1->week = static::$_week;
		$model1->year = static::$_year;
		$model1->status = Core_Constants::STATUS_DISABLED;
		$em->persist ( $model1 );
		$em->flush ();
	}
	
	public static function getPlanRetaintionAmount($agency) {
		$retaintionAmount = 0;
		$plan = static::getCurrentPlan ( $agency, 'agency' );
		
		if ($plan && $plan->pricePlan->redemption)
			$retaintionAmount = $plan->pricePlan->redemption->redemptionAmount;
		return $retaintionAmount;
	}
	
	public static function getPlanRetaintionPercentage($agency) {
		$retaintionAmount = 0;
		$plan = static::getCurrentPlan ( $agency, 'agency' );
		if ($plan && $plan->pricePlan->redemption)
			$retaintionAmount = $plan->pricePlan->redemption->redemptionPercentage;
		return $retaintionAmount;
	}
	
	public static function getVatPercentage($agency) {
		$retaintionAmount = 0;
		$plan = static::getCurrentPlan ( $agency, 'agency' );
		if ($plan && $plan->pricePlan->redemption)
			$retaintionAmount = $plan->pricePlan->vat;
		return $retaintionAmount;
	}
	
	public static function getCurrentPlan($userOrAgency, $type = 'agency') {
		$date = date ( 'Y-m-d' );
		$plan = Price_Model_PlanService::getDetailedPlanResult ( $userOrAgency->id, $date, $type ); //check it
		return $plan;
	}
	
	public static function caluclateAgencyRetaintion($agency) {
		$startDate = date ( 'Y-m-d' );
		$endDate = date ( 'Y-m-d', strtotime ( '+1 day' ) );
		
		$em = static::getDoctrine ();
		$dql = 'select sum(u.amount) from Entities\\Entity\\AgencyRedemption u join u.agency u1 where u1.id = ' . $agency->id . ' and u.date >= \'' . $startDate . "' and u.date < '" . $endDate . "'";
		
		$query = $em->createQuery ( $dql );
		return $query->getSingleScalarResult ();
	
	}
	
	public static function caluclateAgencyRetaintionTrack($agency) {
		$em = static::getDoctrine ();
		$dql = 'select sum(u.amount) from Entities\\Entity\\AgencyRetaintionTrack u join u.agency u1 where u1.id =' . $agency->id;
		$query = $em->createQuery ( $dql );
		return $query->getSingleScalarResult ();
	}
	
	public static function caluclateAgencyInvoiceAmount($agency) {
		$startDate = date ( 'Y-m-d' );
		$endDate = date ( 'Y-m-d', strtotime ( '+1 day' ) );
		
		$em = static::getDoctrine ();
		$dql = 'select sum(u.amount) from Entities\\Entity\\AgencyInvoice u join u.agency u1 where u1.id = ' . $agency->id . ' and u.paymentDate >=\'' . $startDate . "' and u.paymentDate < '" . $endDate . "'";
		$query = $em->createQuery ( $dql );
		return $query->getSingleScalarResult ();
	}
	
	public static function caluclateAgencyDues($agency) {
		$em = static::getDoctrine ();
		$dql = 'select sum(u.amount) from Entities\\Entity\\AgencyDues u join u.agency u1 where u1.id =' . $agency->id;
		$query = $em->createQuery ( $dql );
		return $query->getSingleScalarResult ();
	}

}
