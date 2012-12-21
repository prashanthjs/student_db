<?php

class Core_View_Helper_SurveyorPlanLink {
	
	private $view;
	
	public function surveyorPlanLink($surveyorId, $text = '') {
		
		$ids = Price_Model_PlansService::surveyorPlansHistory ( $surveyorId );
		if (count ( $ids )) {
			
			$surveyor = array_shift ( $ids );
			if (! $text) {
				$text = $surveyor->pricePlan->planName;
			}
			
			return $this->view->planDetailLink ( $surveyor->pricePlan->id, $text );
		
		}
		return '';
	
	}
	
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}

}