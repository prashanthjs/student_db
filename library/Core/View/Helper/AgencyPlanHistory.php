<?php

class Core_View_Helper_AgencyPlanHistory{
	
	private $view; 
	
public function agencyPlanHistory($agencyId,$text = "Plans History") {
	$ids = Price_Model_PlansService::agencyPlansHistory($agencyId);
	
	$ids = Api_Model_Service::getAgencies(null);
	if(Core_Constants::isSurveyor()  ){
		
		return $text;
	}
	
	if(!in_array($agencyId, $ids) ){
		return $text;
	}
	
	if(count($ids)){
		
		$agency=array_shift($ids);
		$url = $this->view->baseUrl().'/price/plans/agency-plan-history/id/'.$agencyId.'/format/ajax';
	
		if(!$text){
			$text = $agency->pricePlan->planName;
		}
		
	
		$html [] = '<a href ="' . $url . '" class = "zoombox w900 h550">';
		$html [] ='<img src="'.$this->view->baseUrl().'/includes/img/icons/search.png" />'. $text;
		$html [] = '</a>';
		return join ( PHP_EOL, $html );
		
	
	}
	return '';
		
	}
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}