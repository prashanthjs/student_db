<?php 
class Core_Form_Element_Paragraph extends Zend_Form_Element
{
    public $helper = 'paragraph';
    public function init()
    {
        $view = $this->getView();
    }
    public function setValue($value)
    {
    	return $this;
    }
    
    public function setDefault($value){
    	$this->_value = $value;
    	return $this;
    	
    }
}
