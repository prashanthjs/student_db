<?php

class Core_View_Helper_DashboardSurveyStatusLink {
	
	private $view;
	
	public function dashboardSurveyStatusLink($text, $startDate, $id) {
	    return $text;
// 		$html = array ();
// 		$request = Zend_Controller_Front::getInstance ()->getRequest ();
// 		$user = Core_Constants::getUser ();
		
		
// 		$userType = $request->getParam ( 'userType', 'agency' );
// 		$id1 = $request->getParam ( 'id', null );
		
// 		if ($userType == 'agency' && !Core_Constants::isSurveyor() ) {
// 			$agencies = Api_Model_Service::getAgencies ( null, true );
// 			if (Core_Constants::isSuperPowerAdmin () || in_array ( $id1, $agencies )) {
// 				if (Core_Constants::isSuperPowerAdmin () && !$id1) {
					
//  					$url = $this->view->baseUrl () . '/product/survey/index?submitstartdate=' . $startDate . '&surveyStatus=' . $id;
//  				} else {
// 					$model = User_Model_AgencyService::getRecord ( $id1 );
// 					$url = $this->view->baseUrl () . '/product/survey/index?submitstartdate=' . $startDate . '&surveyStatus=' . $id . '&agency=' . $model->name . '(' . $model->id . ')';
// 				}
// 				$html [] = '<a href ="' . $url . '" >';
// 				$html [] = $text;
// 				$html [] = '</a>';
// 				return join ( PHP_EOL, $html );
// 			} else {
// 				return $text;
// 			}
// 		} 

// 		else {
			
			
// 			if ($user->id == $id1 || ! Core_Constants::isSurveyor ()) {
				
// 				if ($user->id != $id1) {
// 				$user = User_Model_Service::getRecord($id1);
// 				}
				
// 				$url = $this->view->baseUrl () . '/product/survey/index?submitstartdate=' . $startDate . '&surveyStatus=' . $id . '&surveyor=' . $user->name . '(' . $user->email . ')';
// 				$html [] = '<a href ="' . $url . '" >';
// 				$html [] = $text;
// 				$html [] = '</a>';
// 				return join ( PHP_EOL, $html );
// 			} else {
// 				return $text;
// 			}
// 		}
	
	}
	
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}

}