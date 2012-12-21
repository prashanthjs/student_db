<?php

class Core_View_Helper_DashboardSurveyPriorityLink {
	
	private $view; 
	
// public function dashboardSurveyPriorityLink($text, $startDate, $id) {
// 		$html = array ();
// 		$request = Zend_Controller_Front::getInstance()->getRequest();
// 		$user = Core_Constants::getUser();
		
// 		$userType = $request->getParam('userType','agency');
// 		$id1 = $request->getParam('id',null);
// 		if($userType == 'agency' && !Core_Constants::isSurveyor()){
// 			$agencies = Api_Model_Service::getAgencies(null, true);
// 			if( Core_Constants::isSuperPowerAdmin() || in_array($id1, $agencies) ){
// 				if(Core_Constants::isSuperPowerAdmin()){
// 					$url = $this->view->baseUrl () . '/product/survey/index?submitstartdate=' . $startDate . '&priority=' . $id;
// 				}
// 				else{
// 					$model = User_Model_AgencyService::getRecord($id1);
// 					$url = $this->view->baseUrl () . '/product/survey/index?submitstartdate=' . $startDate . '&priority=' . $id. '&agency='.$model->name.'('.$model->id.')';
// 				}
// 				$html [] = '<a href ="' . $url . '" >';
// 				$html [] = $text;
// 				$html [] = '</a>';
// 				return join ( PHP_EOL, $html );
// 			}
// 			else{
// 				return $text;
// 			}
// 		}
		
// 		else{
			
// 			if($user->id == $id1 ||!Core_Constants::isSurveyor()){
// 				if ($user->id != $id1) {
// 					$user = User_Model_Service::getRecord($id1);
// 				}
				
// 				$url = $this->view->baseUrl () . '/product/survey/index?submitstartdate=' . $startDate . '&priority=' . $id. '&surveyor='.$user->name.'('.$user->email.')';
// 				$html [] = '<a href ="' . $url . '" >';
// 				$html [] = $text;
// 				$html [] = '</a>';
// 				return join ( PHP_EOL, $html );
// 			}
// 			else{
// 				return $text;
// 			}
// 		}
// 		return $text;
// 	}

	public function dashboardSurveyPriorityLink($text, $startDate, $id) {
	    
	    return $text;
	}
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

}