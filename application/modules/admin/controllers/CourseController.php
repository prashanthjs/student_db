<?php
class Admin_CourseController extends Core_Controller_Grid {
	
	protected $_createForm = 'Admin_Form_Course';

	protected $_model = 'Admin_Model_CourseService';
	
	protected $_indexPath = 'indexwoenableanddisable.phtml';
	protected $_title = 'Manage Courses';
	

}

