<?php

class Core_View_Helper_ChangePasswordLink {
	
	private $view; 
	
	public function changePasswordLink( $id) {
		
		$rurl = $this->view->curPageUrl ();
		
		$url = $this->view->url ( array ('module' => 'user', 'controller' => 'user', 'action' => 'change-password', 'id' => $id, 'format' => 'ajax' ) );
		
		
		$html = array();
		$html [] = '<a title="change password" href ="' . $url . '"  class="zoombox w900 h550" >';
		$html [] = '<img alt=""	src="'.$this->view->baseUrl().'/includes/img/icons/private.png">';
		$html [] = '</a>';
		
		return join ( PHP_EOL, $html );
	
	}
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}