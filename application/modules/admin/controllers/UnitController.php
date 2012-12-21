<?php
class Admin_UnitController extends Core_Controller_Grid {
	
	protected $_createForm = 'Admin_Form_Unit';

	protected $_model = 'Admin_Model_UnitService';
	
	protected $_indexPath = 'indexwoenableanddisable.phtml';
	protected $_title = 'Manage Units';
	

}

