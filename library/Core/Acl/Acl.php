<?php

class Core_Acl_Acl extends Zend_Acl {
	
	public function __construct() {
		$surveyor = Core_Constants::SURVEYOR . 's';
		$admin = Core_Constants::ADMIN . 's';
		$pAdmin = Core_Constants::POWER_ADMIN . 's';
		$spAdmin = Core_Constants::SUPER_POWER_ADMIN . 's';
		
		$this->addRole ( new Zend_Acl_Role ( 'guest' ) );
		$this->addRole ( new Zend_Acl_Role ( $surveyor ), 'guest' );
		$this->addRole ( new Zend_Acl_Role ( $admin ), $surveyor );
		$this->addRole ( new Zend_Acl_Role ( $pAdmin ), $admin );
		$this->addRole ( new Zend_Acl_Role ( $spAdmin ), $pAdmin );
		
		$this->add ( new Zend_Acl_Resource ( 'default' ) )->add ( new Zend_Acl_Resource ( 'default:index' ), 'default' );
		$this->add ( new Zend_Acl_Resource ( 'user' ) )->add ( new Zend_Acl_Resource ( 'user:user' ), 'user' )->add ( new Zend_Acl_Resource ( 'user:agency' ), 'user' )->add ( new Zend_Acl_Resource ( 'user:surveyor' ), 'user' );
		$this->add ( new Zend_Acl_Resource ( 'product' ) )->add ( new Zend_Acl_Resource ( 'product:survey' ), 'product' )->add ( new Zend_Acl_Resource ( 'product:survey-individual' ), 'product' )->add ( new Zend_Acl_Resource ( 'product:surveyor-availabilty' ), 'product' );
		
		$this->add ( new Zend_Acl_Resource ( 'admin' ) );
		$this->add ( new Zend_Acl_Resource ( 'admin:survey-status' ), 'admin' );
		$this->add ( new Zend_Acl_Resource ( 'admin:priority' ), 'admin' );
		$this->add ( new Zend_Acl_Resource ( 'admin:installation' ), 'admin' );
		$this->add ( new Zend_Acl_Resource ( 'admin:job-role' ), 'admin' );
		
		$this->add ( new Zend_Acl_Resource ( 'download' ) );
		
		
		$this->add ( new Zend_Acl_Resource ( 'price' ) );
		$this->add ( new Zend_Acl_Resource ( 'price:plan' ), 'price' );
		$this->add ( new Zend_Acl_Resource ( 'price:plans' ), 'price' );
		$this->add ( new Zend_Acl_Resource ( 'price:surveyor-amount' ), 'price' );
		$this->add ( new Zend_Acl_Resource ( 'price:surveyor-invoice' ), 'price' );
		$this->add ( new Zend_Acl_Resource ( 'price:surveyor-retaintion' ), 'price' );
		$this->add ( new Zend_Acl_Resource ( 'price:surveyor-retaintion-track' ), 'price' );
		
		$this->add ( new Zend_Acl_Resource ( 'price:agency-amount' ), 'price' );
		$this->add ( new Zend_Acl_Resource ( 'price:agency-invoice' ), 'price' );
		$this->add ( new Zend_Acl_Resource ( 'price:agency-retaintion' ), 'price' );
		
		$this->add ( new Zend_Acl_Resource ( 'payment' ));
		$this->add ( new Zend_Acl_Resource ( 'payment:paid' ), 'payment');
		$this->add ( new Zend_Acl_Resource ( 'payment:cancelled' ), 'payment');
		
		$this->allow ( $surveyor, 'default:index', array ('index' ) );
		$this->allow ( $pAdmin, 'user:user', array ('index', 'create' ) );
		$this->allow ( $pAdmin, 'user:agency', array ('index', 'create' ) );
		$this->allow ( $admin, 'user:surveyor', array ('index', 'create' ) );
		$this->allow ($surveyor, 'product:survey', array ('index', 'create' ) );
		$this->allow ($surveyor, 'product:survey-individual', array ('index', 'create' ) );
		$this->allow ( $surveyor, 'product:surveyor-availabilty', array ('index', 'create' ) );
		
		$this->allow ( $spAdmin, 'admin:survey-status', array ('index', 'create' ) );
		$this->allow ( $spAdmin, 'admin:job-role', array ('index', 'create' ) );
		$this->allow ( $spAdmin, 'admin:priority', array ('index', 'create' ) );
		$this->allow ( $spAdmin, 'admin:installation', array ('index', 'create' ) );
		
		$this->allow($surveyor, 'price:plan','index');
		$this->allow ( $pAdmin, 'price:plan',  'create'  );
		$this->allow ( $pAdmin, 'price:plans',  'index'  );
		
		
		$this->allow ( $surveyor, 'price:surveyor-amount',  'index'  );
		$this->allow ( $surveyor, 'price:surveyor-invoice',  'index'  );
		$this->allow ( $surveyor, 'price:surveyor-retaintion',  'index'  );
		
		
		$this->deny ( $admin, 'price:surveyor-amount',  'index'  );
		$this->deny ( $admin, 'price:surveyor-invoice',  'index'  );
		$this->deny ( $admin, 'price:surveyor-retaintion',  'index'  );
		
		$this->allow ( $pAdmin, 'price:surveyor-amount',  'index'  );
		$this->allow ( $pAdmin, 'price:surveyor-invoice',  'index'  );
		$this->allow ( $pAdmin, 'price:surveyor-retaintion',  'index'  );
		$this->allow ( $pAdmin, 'price:surveyor-retaintion-track',  'index'  );
		
		
		$this->allow ( $pAdmin, 'price:agency-amount',  'index'  );
		$this->allow ( $pAdmin, 'price:agency-invoice',  'index'  );
		$this->allow ( $pAdmin, 'price:agency-retaintion',  'index'  );
		
		$this->allow ( $pAdmin, 'payment:paid',  'create'  );
		$this->allow ( $pAdmin, 'payment:cancelled',  'create'  );
		
		
		$this->allow ( $surveyor, 'download',  array('excel','index')  );
		
		
	}

}