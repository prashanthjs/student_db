<?php

class Core_View_Helper_PlanDetailLink {
	
	private $view;
	
	public function planDetailLink($id, $text) {
		
		
		
		$url = $this->view->baseUrl () . '/price/plans/price-history/id/' . $id . '/format/ajax';
		
		$html [] = '<a href ="' . $url . '" class = "zoombox w900 h550">';
		$html [] = $text;
		$html [] = '</a>';
		return join ( PHP_EOL, $html );
	
	}
	
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}

}