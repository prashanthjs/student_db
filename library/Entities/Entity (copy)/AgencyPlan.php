<?php
namespace Entities\Entity;

/**
 * AgencyPlan
 *
 * @Table(name="agency_plan")
 * @Entity @HasLifecycleCallbacks
 */
class AgencyPlan {
	/**
	 * @var integer $id
	 *
	 * @Column(name="id", type="integer", nullable=false)
	 * @Id
	 * @GeneratedValue(strategy="IDENTITY")
	 */
	private $id;
	
	/**
	 * @var datetime $startdate
	 *
	 * @Column(name="start_date", type="datetime", nullable=false)
	 */
	private $startDate;
	
	/**
	 * @var PricePlan
	 *
	 * @ManyToOne(targetEntity="PricePlan")
	 * @JoinColumns({
	 * @JoinColumn(name="price_plan_id", referencedColumnName="id")
	 * })
	 */
	private $pricePlan;
	
	/**
	 * @var Agency
	 *
	 * @ManyToOne(targetEntity="Agency")
	 * @JoinColumns({
	 * @JoinColumn(name="agency_id", referencedColumnName="id")
	 * })
	 */
	private $agency;
	
	public function __get($property) {
		
		if (\method_exists ( $this, "get" . \ucfirst ( $property ) )) {
			$method_name = "get" . \ucfirst ( $property );
			return $this->$method_name ();
		} else
			return $this->$property;
	}
	
	public function __set($property, $value) {
		if (\method_exists ( $this, "set" . \ucfirst ( $property ) )) {
			$method_name = "set" . \ucfirst ( $property );
			
			$this->$method_name ( $value );
		} else
			$this->$property = $value;
	}
	
	public function getModel($id, $model) {
		if (! is_object ( $id )) {
			$em = \Zend_Registry::get ( 'doctrine' )->getEntityManager ();
			$model = $em->find ( $model, $id );
			if ($model) {
				return $model;
			} else {
				return false;
			}
		} else {
			return $id;
		}
	}
	
	//public function setS
	
	/**
	 * @param \Entities\Entity\Agency $agency
	 */
	public function setPricePlan($pricePlan) {
		$model = $this->getModel ( $pricePlan, 'Entities\Entity\PricePlan' );
		if ($model)
			$this->pricePlan = $model;
	}
	
	/**
	 * @param \Entities\Entity\Agency $agency
	 */
	public function setAgency($agency) {
		$model = $this->getModel ( $agency, 'Entities\Entity\Agency' );
		if ($model)
			$this->agency = $model;
	}
	
	
	
	
	/**
	 * @PrePersist
	 * @PreUpdate
	 * 
	 */
	public function setStartDate2() {
		$this->startDate = new \DateTime ();
	}

}