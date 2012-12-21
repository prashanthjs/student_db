<?php

class Zend_View_Helper_Custom_SurveyLink {
	
	private $view; 
	
	public function surveyLink($text, $id) {
		$html = array ();
		$params = array();
		$params['action'] = 'view';
		$params['controller'] = 'survey-individual';
		$params['module'] = 'product';
		$params['id'] = $id; 
		
		$url = $this->view->url($params, null, true);
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