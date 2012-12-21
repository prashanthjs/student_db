<?php

class Default_Model_ModifyPaymentService extends Core_Model_Service {
	
	protected static $_model = "Entities\\Entity\\SurveyorRetaintionTrack";
	protected static $_agencies = array ();
	protected static $_week = 1;
	protected static $_year = 2012;
	
	protected static $_amount;
	protected static $_priority;
	protected static $_percentage;
	
	public static function modifyTotalAmount($week, $year, $amount, $priority, $percentage) {
		static::$_week = $week;
		static::$_year = $year;
		static::$_amount = $amount;
		static::$_priority = $priority;
		static::$_percentage = $percentage;
		
		$em = static::getDoctrine ();
		$dql = 'select u from Entities\\Entity\\Surveyor u join u.user u1 order by u.id asc';
		$query = $em->createQuery ( $dql );
		$results = $query->getResult ();
		foreach ( $results as $result ) {
			static::modifySurveyorInvoiceAmount ( $result->user );
		}
	}
	
	public static function modifySurveyorInvoiceAmount($surveyor) {
		$startDate = date ( 'Y-m-d' );
		$endDate = date ( 'Y-m-d', strtotime ( '+1 day' ) );
		
		$em = static::getDoctrine ();
		
		$dql1 = 'select count(u) from Entities\\Entity\\SurveyorInvoice u join u.user u1 join u.survey s  where u1.id = ' . $surveyor->id . ' and u.paymentDate >=\'' . $startDate . "' and u.paymentDate < '" . $endDate . "'";
		$query1 = $em->createQuery ( $dql1 );
		$total = $query1->getSingleScalarResult();
		
		
		$dql = 'select u from Entities\\Entity\\SurveyorInvoice u join u.user u1 join u.survey s join s.priority sp where u1.id = ' . $surveyor->id . ' and u.paymentDate >=\'' . $startDate . "' and u.paymentDate < '" . $endDate . "' and sp.id = " . static::$_priority;
		$query = $em->createQuery ( $dql );
		$results = $query->getResult ();
		
		
		
		$maxElements = floor ( $total * (static::$_percentage) / 100 );
	
		$i = 0;
		
		foreach ( $results as $result ) {
			
			if ($i <= $maxElements) {
				$i++;
				continue;
			}
			$result->amount = static::$_amount;
			$em->persist ( $result );
			$em->flush ();
			$i++;
			
		}
		
		return true;
	}

}
