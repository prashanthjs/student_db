<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	
	protected $_acl;
	protected $_role = 'guest';
	
	protected function _initAcl() {
		
		$locale = new Zend_Locale('en_GB');
		Zend_Registry::set('Zend_Locale', $locale);
		
		
		//$fc = Zend_Controller_Front::getInstance();
	}
	
	
	
	protected function _initConfig() {
		$config = new Zend_Config ( $this->getOptions (), true );
		Zend_Registry::set ( 'config', $config );
		return $config;
	}
	
	protected function _initAutoloader() {
		$autoloader = new Zend_Application_Module_Autoloader ( array ('namespace' => 'Core_', 'basePath' => dirname ( __FILE__ ) ) );
		$autoloader->addResourceType ( 'validator', 'validators', 'Validate' );
		$autoloader->addResourceType ( 'helper', 'helpers', 'Helper' );
		return $autoloader;
	}
	
	protected function _initNavigation() {
		$this->bootstrap ( 'layout' );
		$layout = $this->getResource ( "layout" );
		$view = $layout->getView ();
	
		ZendX_JQuery::enableView ( $view );
		$view->headTitle ( 'Student Information System' );
		$menu = APPLICATION_PATH . '/configs/navigation.xml';
		$nav_config = new Zend_Config_Xml ( $menu, 'nav' );
		$navigation = new Zend_Navigation ( $nav_config );
	
		$view->navigation ( $navigation );

	}
	
	
}

