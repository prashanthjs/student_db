<?php

class Core_View_Helper_SurveyorPlanHistoryLink {
	
	private $view; 
	
	public function surveyorPlanHistoryLink( $id) {
		
		
		$html = '<img alt="plan history"	src="'.$this->view->baseUrl().'/includes/img/icons/money-bundle.png">';
		
		
		return $this->view->surveyorPlanHistory($id, $html);
	
	}
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}