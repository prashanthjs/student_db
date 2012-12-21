<?php

class Core_View_Helper_EnableLink {
	
	private $view; 
	
	public function enableLink( $id) {
		$rurl = $this->view->curPageUrl ();
		$url = $href = $this->view->url ( array ( 'action' => 'enable', 'id' => $id, 'format' => 'ajax' ) );;
		$html = array();
		$html [] = '<a   href ="' . $url . '" class = "zoombox w400 h100"  >';
		$html [] = '<img alt=""	src="'.$this->view->baseUrl().'/includes/img/icons/button-check.png">';
		$html [] = '</a>';
		
		return join ( PHP_EOL, $html );
	
	}
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}