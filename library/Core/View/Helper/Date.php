<?php

class Core_View_Helper_Date {
	
	private $view; 
	
	public function date($value) {
		
		if(is_a($value,'DateTime')){
			$value1=$value->format('d/m/Y');
		}
		else{
			$value1 = $value ;
		}
		return $value1;
	}
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}