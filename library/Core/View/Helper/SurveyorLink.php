<?php

class Core_View_Helper_SurveyorLink {
	
	private $view; 
	
	public function surveyorLink($text, $id) {
		$html = array ();
		$params = array();
		$params['action'] = 'surveyor';
		$params['controller'] = 'index';
		$params['module'] = 'default';
		$params['id'] = $id; 
		
		$url = $this->view->baseUrl().'/default/index/surveyor?id='.$id;//$this->view->url($params, null, true);
		$html [] = '<a href ="' . $url . '" >';
		$html [] = $text;
		$html [] = '</a>';
		
		return join ( PHP_EOL, $html );
	
	}
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}