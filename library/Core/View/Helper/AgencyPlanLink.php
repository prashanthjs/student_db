<?php

class Core_View_Helper_AgencyPlanLink {
	
	private $view;
	
	public function agencyPlanLink($agencyId, $text = '') {
		$ids = Price_Model_PlansService::agencyPlansHistory ( $agencyId );
		
		
		if (count ( $ids )) {
			
			$agency = array_shift ( $ids );
			if (! $text) {
				$text = $agency->pricePlan->planName;
			}
			
			
			if(Core_Constants::isSurveyor()  ){
			
				return $text;
			}
			$agencyIds = Api_Model_Service::getAgencies(null); 
			if(!in_array($agencyId, $agencyIds) ){
				return $text;
			}
			
			return $this->view->planDetailLink($agency->pricePlan->id,$text);
			
		}
		return '';
	
	}
	
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}

}