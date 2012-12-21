<?php

class Login_Model_Login extends Core_Model_Service {
	
	public static $_model = 'Entities\Entity\User';
	
	public static function authenticate($email, $password) {
		$em = static::getDoctrine ();
		$dql = 'select u from ' . static::$_model . ' u  where u.username = ?1 and u.password = ?2';
		
		$query = $em->createQuery ( $dql );
		$query->setParameter ( 1, $email );
		$query->setParameter ( 2, md5 ( $password ) );
		
		$result = $query->getSingleResult ();
		
		if (! $result) {
			throw new Exception ( 'ACCOUNT NOT FOUND' );
		}
		
		return $result;
	
	}

}