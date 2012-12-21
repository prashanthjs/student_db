<?php

class Zend_View_Helper_Custom_UserLink {
	
	private $view; 
	
public function userLink($text, $id) {
		$html = array ();
		$url = $this->view->baseUrl().'/default/index/admin/id/'.$id;//$this->view->url($params, null, true);
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