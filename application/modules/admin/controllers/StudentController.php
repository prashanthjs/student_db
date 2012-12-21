<?php
class Admin_StudentController extends Core_Controller_Grid {
	
	protected $_createForm = 'Admin_Form_Student';

	protected $_model = 'Admin_Model_StudentService';
	
	protected $_indexPath = 'indexwoenableanddisable.phtml';
	protected $_title = 'Manage Student';
	

}

