<?php

class Core_View_Helper_DashboardSurveyPriceLink {
	
	private $view; 
	
// public function dashboardSurveyPriceLink($text, $startDate, $id, $linkDisplay = true) {
// 		$html = array ();
// 		//$url = $this->view->baseUrl().'/default/index/admin/id/'.$id;//$this->view->url($params, null, true);
		
// 		if(!$linkDisplay){
// 			return $text;
// 		}
		
// 		$url = $this->view->baseUrl().'/price/plan/index?submitstartdate='.$startDate.'&priority='.$id;
		
		
// 		$html [] = '<a href ="' . $url . '"  >';
// 		$html [] = $text;
// 		$html [] = '</a>';
// 		return join ( PHP_EOL, $html );
// 	}

	public function dashboardSurveyPriceLink($text, $startDate, $id, $linkDisplay = true) {
	  
	        return $text;
	  }
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}