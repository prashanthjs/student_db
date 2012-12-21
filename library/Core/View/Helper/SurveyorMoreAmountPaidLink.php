<?php

class Core_View_Helper_SurveyorMoreAmountPaidLink {
	
	private $view; 
	
	public function surveyorMoreAmountPaidLink( $user, $text = "more") {
		$url = $this->view->url ( array ( 'module'=>'price','controller' => 'surveyor-amount','action' => 'index', 'surveyor' => $user->name.'('.$user->email. ')') );
		$html = array();
		$html [] = '<a title="more" href ="' . $url . '"   >';
		$html [] = $text;
		$html [] = '</a>';
		return join ( PHP_EOL, $html );
	}
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}