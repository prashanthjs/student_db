<?php
class Admin_Model_StudentService extends Core_Model_Grid {
	public static $_model = "Entities\Entity\StudentView";
	protected static $_columns = array (
			'firstName',
			'lastName',
			'dob',
			'level',
			'homeAddress',
			'termAddress',
			'country' 
	);
	protected static $_dispalyColumns = array (
			'u_id' => array (
					'key' => 'u_id',
					'title' => 'ID',
					'type' => 'number',
					'width' => '100',
					'visible' => true,
					'type' => 'string' 
			),
			
			'u_firstName' => array (
					'key' => 'u_firstName',
					'title' => 'First Name',
					'type' => 'string' 
			),
			
			'u_lastName' => array (
					'key' => 'u_lastName',
					'title' => 'Last Name',
					'type' => 'string' 
			),
			'u_dob' => array (
					'key' => 'u_dob',
					'title' => 'Date of Birth',
					'type' => 'date',
					'options' => array (
							'template' => "#= kendo.toString(u_dob, 'dd/MM/yyyy') #" 
					) 
			),
			'u_country' => array (
					'key' => 'u_country',
					'title' => 'Country',
					'type' => 'string'
			),
			'u_homeAddress' => array (
					'key' => 'u_homeAddress',
					'title' => 'Home Address',
					'type' => 'string'
			),
			'u_termAddress' => array (
					'key' => 'u_termAddress',
					'title' => 'Term Address',
					'type' => 'string'
			),
				
			
			'u_level' => array (
					'key' => 'u_level',
					'title' => 'Level of degree',
					'type' => 'string' 
			),
			'u_course' => array (
					'key' => 'u_course',
					'title' => 'Course',
					'type' => 'string' 
			),
			'u_unit' => array (
					'key' => 'u_unit',
					'title' => 'Units',
					'type' => 'string' 
			) 
	);
	public static function getRecordArray($id) {
		$model = static::getRecord ( $id );
		
		$values = parent::getRecordArray ( $id );
		
		$values ['course'] = static::getCourses ( $id );
		$values ['unit'] = static::getUnits ( $id );
		return $values;
	}
	public static function getCourses($id) {
		$em = static::getDoctrine ();
		$dql = 'select u from \Entities\Entity\StudentCourse u join u.student u1 where u1.id =  ' . $id;
		$query = $em->createQuery ( $dql );
		
		$results = $query->getResult ();
		
		$array = array ();
		
		foreach ( $results as $result ) {
			if ($result->course) {
				$array [] = $result->course->id;
			}
		}
		return $array;
	}
	public static function getUnits($id) {
		$em = static::getDoctrine ();
		$dql = 'select u from \Entities\Entity\StudentUnit u join u.student u1 where u1.id =  ' . $id;
		$query = $em->createQuery ( $dql );
		
		$results = $query->getResult ();
		
		$array = array ();
		
		foreach ( $results as $result ) {
			if ($result->unit) {
				$array [] = $result->unit->id;
			}
		}
		return $array;
	}
	public static function save($params) {
		static::$_model = 'Entities\Entity\Student';
		
		$em = static::getDoctrine ();
		
		$model = parent::save ( $params );
		
		static::updateCourses ( $params ['course'], $model );
		static::updateUnits ( $params ['unit'], $model );
		
		return $model;
	}
	public function updateCourses($courses, $model) {
		$em = static::getDoctrine ();
		$dql = 'delete from \Entities\Entity\StudentCourse u where u.student = ' . $model->id;
		$query = $em->createQuery ( $dql );
		$result = $query->getResult ();
		
		foreach ( $courses as $course ) {
			
			$c = $em->find ( '\Entities\Entity\Course', $course );
			if ($c) {
				$sc = new \Entities\Entity\StudentCourse ();
				$sc->student = $model;
				$sc->course = $c;
				$em->persist ( $sc );
				$em->flush ();
			}
		}
	}
	public function updateUnits($units, $model) {
		$em = static::getDoctrine ();
		$dql = 'delete from \Entities\Entity\StudentUnit u where u.student = ' . $model->id;
		$query = $em->createQuery ( $dql );
		$result = $query->getResult ();
		
		foreach ( $units as $unit ) {
			
			$u = $em->find ( '\Entities\Entity\Unit', $unit );
			if ($u) {
				$sc = new \Entities\Entity\StudentUnit ();
				$sc->student = $model;
				$sc->unit = $u;
				$em->persist ( $sc );
				$em->flush ();
			}
		}
	}
	public static function delete($id) {
		static::$_model = 'Entities\Entity\Student';
		$em = static::getDoctrine ();
		
		$dql = 'delete from \Entities\Entity\StudentCourse u where u.student = ' . $id;
		$query = $em->createQuery ( $dql );
		$result = $query->getResult ();
		
		$dql = 'delete from \Entities\Entity\StudentUnit u where u.student = ' . $id;
		$query = $em->createQuery ( $dql );
		$result = $query->getResult ();
		
		return parent::delete ( $id );
	}
}
