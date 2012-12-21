<?php

class Core_View_Helper_PerPage {
	
	private $view;
	
	public function setView($view) {
		$this->_view = $view;
	}
	public function perPage() {
		
		$request = Zend_Controller_Front::getInstance ()->getRequest ();
		
		$params = $request->getParams ();
		
		foreach($params as $key=>$value){
			try{
			$date = DateTime::createFromFormat('d/m/Y', $value);
			if($date){
			$params[$key] = $date->format('Y-m-d');
			}
			}
			catch(Exception $e){
				
			}
			
			
		}
// 		var_dump($params);
// 		exit;
		$current = $request->getParam('perPage','30');
		
		$html [] = '<div class="pagination">';
		
		$params ['perPage'] = 10;		
		$link = $this->_view->url ( $params, null, false );
		$class = '';
		if($current == $params['perPage'])
		$class = 'class="current"';
		$html [] = '<a href="' . $link . '#result-block"'.$class.'>10</a>';
		
		
		$params ['perPage'] = 20;
		$link = $this->_view->url ( $params, null, false );
		$class = '';
		if($current == $params['perPage'])
			$class = 'class="current"';
		$html [] = '<a href="' . $link . '#result-block"'.$class.'  >20</a>';
		
		$params ['perPage'] = 30;
		$link = $this->_view->url ( $params, null, false );
		$class = '';
		
		if($current == $params['perPage'])
			$class = 'class="current"';
		$html [] = '<a href="' . $link . '#result-block" '.$class.'  >30</a>';
		
		$params ['perPage'] = 10000;
		
		
		
		$link = $this->_view->url ( $params, null, false );
		$class = '';
		if($current == $params['perPage'])
			$class = 'class="current"';
		$html [] = '<a href="' . $link . '#result-block" '.$class.' >All</a>';
		$html [] = '</div>';
		
		return join ( PHP_EOL, $html );
	
	}

}