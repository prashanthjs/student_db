<?php

class Zend_View_Helper_Custom_Pagination {
	
	private $view;
	
	public function setView($view) {
		$this->_view = $view;
	}
	public function pagination($total, $perPage = 10) {
		
		$request = Zend_Controller_Front::getInstance ()->getRequest ();
		
		$params = $request->getParams ();
		
		if (! isset ( $params ['page'] )) {
			$currentPage = $params ['page'] = 1;
		} else {
			$currentPage = $params ['page'];
		}
		
		$html = array ();
		
		if ($total < $perPage) {
			return '';
		}
		
		$html [] = '<div class="pagination">';
		$pageNo = 1;
		for($i = 1; $i <= $total; $i = $i + $perPage) {
			$params ['page'] = $pageNo;
			$link = $this->_view->url ( $params, null, false );
			if ($pageNo == $currentPage)
				$html [] = '<a href="' . $link . '" class="current">' . $pageNo . '</a>';
			else
				$html [] = '<a href="' . $link . '" >' . $pageNo . '</a>';
			$pageNo ++;
		}
		$html [] = '</div>';
		
		return join ( PHP_EOL, $html );
	
	}

}