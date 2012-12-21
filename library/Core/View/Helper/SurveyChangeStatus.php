<?php

class Core_View_Helper_SurveyChangeStatus {
	
	private $view; 
	
	public function editLink( $id) {
		$url = $this->view->url ( array ( 'action' => 'change-status', 'id' => $id, 'format' => 'ajax' ) );
		$html = array();
		$html [] = '<a title="Edit" href ="' . $url . '"  class="zoombox w900 h550" >';
		$html [] = '<img alt=""	src="'.$this->view->baseUrl().'/includes/img/icons/actions/edit.png">';
		$html [] = '</a>';
		
		return join ( PHP_EOL, $html );
	
	}
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}