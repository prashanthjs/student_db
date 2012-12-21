<?php

class Core_View_Helper_AgencyMoreCancellationLink {
	
	private $view;
	
	public function agencyMoreCancellationLink($agency, $text = "more") {
		$agencyName = '';
		if ($agency) {
			$agencyName = $agency->name . '(' . $agency->id . ')';
		}
		
		$url = $this->view->url ( array ('module' => 'price', 'controller' => 'surveyor-retaintion', 'action' => 'index', 'agency' => $agencyName ) );
		$html = array ();
		$html [] = '<a title="more" href ="' . $url . '"   >';
		$html [] = $text;
		$html [] = '</a>';
		return join ( PHP_EOL, $html );
	}
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}

}