<?php

class Core_View_Helper_DashboardSurveyPriorityMoreLink {
	
	private $view; 
	
public function dashboardSurveyPriorityMoreLink($text,  $id, $userType,$display) {
		$html = array ();
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$userType = $request->getParam('userType','agency');
		$id1 = $request->getParam('id',null);
		//$url = $this->view->baseUrl().'/default/index/survey-priority/format/ajax/#'.$display.'?usertype='.$userType.'&id='.$id.'&format=ajax';
		$url = $this->view->baseUrl().'/default/index/survey-priority/?usertype='.$userType.'&id='.$id1.'&format=ajax';
		$html [] = '<a href ="' . $url . '" class ="zoombox w550 h400" >';
		$html [] = $text;
		$html [] = '</a>';
		return join ( PHP_EOL, $html );
	}
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}