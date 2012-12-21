<?php

class Core_View_Helper_SurveyorPlanHistory {
	
	private $view;
	
	public function surveyorPlanHistory($surveyorId, $text = 'Plans History') {
		
		$ids = Price_Model_PlansService::surveyorPlansHistory ( $surveyorId );
		if (count ( $ids )) {
			
			$surveyor = array_shift( $ids );
			$url = $this->view->baseUrl () . '/price/plans/surveyor-plan-history/id/' . $surveyorId . '/format/ajax';
			if(!$text){
			$text = $surveyor->pricePlan->planName;
			}
			$html [] = '<a href ="' . $url . '" class = "zoombox w900 h550">';
			$html [] = '<img alt=""	src="'.$this->view->baseUrl().'/includes/img/icons/search.png">';
			$html [] = $text;
			$html [] = '</a>';
			return join ( PHP_EOL, $html );
		
		}
		return '';
	
	}
	
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}

}