<?php

namespace Entities\Entity;

/**
 * PriceDetail
 *
 * @Table(name="price_detail")
 * @Entity @HasLifecycleCallbacks
 */
class PriceDetail {
	/**
	 * @var integer $id
	 *
	 * @Column(name="id", type="integer", nullable=false)
	 * @Id
	 * @GeneratedValue(strategy="IDENTITY")
	 */
	private $id;
	
	/**
	 * @var integer $price
	 *
	 * @Column(name="price", type="float", nullable=true)
	 */
	private $price;
	
	/**
	 * @var integer $startDate
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
	 * @var Priority
	 *
	 * @ManyToOne(targetEntity="Priority")
	 * @JoinColumns({
	 * @JoinColumn(name="priority_id", referencedColumnName="id")
	 * })
	 */
	private $priority;
	
	/**
	 * @var Installation
	 *
	 * @ManyToOne(targetEntity="Installation")
	 * @JoinColumns({
	 *   @JoinColumn(name="installation_id", referencedColumnName="id")
	 * })
	 */
	private $installation;
	
	public function __get($property) {
		
		if (\method_exists ( $this, "get" .\ucfirst ( $property ) )) {
			$method_name = "get" .\ucfirst ( $property );
			return $this->$method_name ();
		} else
			return $this->$property;
	}
	
	public function __set($property, $value) {
		if (\method_exists ( $this, "set" .\ucfirst ( $property ) )) {
			$method_name = "set" .\ucfirst ( $property );
			
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
	public function setPriority($priority) {
		$model = $this->getModel ( $priority, 'Entities\Entity\Priority' );
		if ($model)
			$this->priority = $model;
	}
	
	/**
	 * @param \Entities\Entity\Agency $agency
	 */
	public function setInstallation($priority) {
		$model = $this->getModel ( $priority, 'Entities\Entity\Installation' );
		if ($model)
			$this->installation = $model;
	}
	
	
	public function setStartDate($value) {
		if ($value)
			$this->startDate = \DateTime::createFromFormat ( 'd/m/Y', $value );
	
	}

}