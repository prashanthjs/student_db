<?php

class Core_View_Helper_UserFileLink {
	
	private $view; 
	
	public function userFileLink($text, $href) {
		$html = array ();
		
		
		$url = $this->view->baseUrl().'/'.'user/files/'.rawurlencode($href);
		
		$html [] = '<a href ="' . $url . '" target="_blank">';
		$html [] = $text;
		$html [] = '</a>';
		
		return join ( PHP_EOL, $html );
	
	}
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}