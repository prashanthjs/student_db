<?php

class Core_View_Helper_disableLink {
	
	private $view; 
	
	public function disableLink( $id) {
		$rurl = $this->view->curPageUrl ();
		$url = $href = $this->view->url ( array ( 'action' => 'disable', 'id' => $id, 'format' => 'ajax' ) );;
		$html = array();
		$html [] = '<a  href ="' . $url . '" class = "zoombox w400 h100" href ="' . $url . '"  >';
		$html [] = '<img alt=""	src="'.$this->view->baseUrl().'/includes/img/icons/button-white-stop.png">';
		$html [] = '</a>';
		
		return join ( PHP_EOL, $html );
	
	}
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}