<?php

/** 
 * @author developer
 * 
 * 
 */
class Core_Plugin_Login extends Zend_Controller_Plugin_Abstract {
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		if($request->getModuleName () == 'default' && $request->getControllerName () == 'cron'){
		return true;	
		}
		
		if (! Zend_Auth::getInstance ()->hasIdentity ()) {
			$request = $this->getRequest ();
			
			if ($request->getModuleName () != 'login') {
				$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper ( 'Redirector' );
				$redirector->direct ( 'index', 'login', 'login' );
			}
		
		}
	
	}
}
