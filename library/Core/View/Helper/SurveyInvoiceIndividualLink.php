<?php

class Core_View_Helper_SurveyInvoiceIndividualLink {
	
	private $view; 
	
	public function surveyInvoiceIndividualLink( $id) {
		$url = $this->view->url ( array ( 'module'=>'price','controller' => 'surveyor-amount','action' => 'show-invoice', 'id' => $id) );
		$html = array();
		$html [] = '<a title="invoice" href ="' . $url . '" target="_blank"  >';
		$html [] = '<img alt=""	src="'.$this->view->baseUrl().'/includes/img/icons/search.png">';
		$html [] = '</a>';
		return join ( PHP_EOL, $html );
	}
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}