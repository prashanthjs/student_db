<?php

class Core_View_Helper_DeleteLink {
	
	private $view; 
	
	public function deleteLink( $id) {
		
		$url = $href = $this->view->url ( array ( 'action' => 'delete', 'id' => $id, 'format' => 'ajax' ) );;
		$html = array();
		$html [] = '<a title="delete" class ="delete" href ="' . $url . '"   >';
		$html [] = '<img alt=""	src="'.$this->view->baseUrl().'/includes/img/icons/actions/delete.png">';
		$html [] = '</a>';
		
		return join ( PHP_EOL, $html );
	
	}
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}