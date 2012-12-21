<?php
class Admin_Model_CourseService extends Core_Model_Grid {
	public static $_model = "Entities\Entity\Course";
	protected static $_columns = array (
			'name' 
	)
	;
	protected static $_dispalyColumns = array (
			'u_id' => array (
					'key' => 'u_id',
					'title' => 'ID',
					'type' => 'number',
					'width' => '100',
					'visible' => true,
					'type' => 'string' 
			),
			
			'u_name' => array (
					'key' => 'u_name',
					'title' => 'Course Name',
					'type' => 'string' 
			),
			'u_createdDate' => array (
					'key' => 'u_createdDate',
					'title' => 'Created Date',
					'type' => 'date',
					'options' => array (
							'template' => "#= kendo.toString(u_createdDate, 'dd/MM/yyyy') #" 
					) 
			),
			
			'u_updatedDate' => array (
					'key' => 'u_updatedDate',
					'title' => 'Updated Date',
					'type' => 'date',
					'options' => array (
							'template' => "#= kendo.toString(u_updatedDate, 'dd/MM/yyyy') #" 
					) 
			) 
	)
	;
}
