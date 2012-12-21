<?php

namespace Entities\Entity;

/**
 * SurveyorAvailability
 *
 * @Table(name="surveyor_availability")
 * @Entity @HasLifecycleCallbacks
 */
class SurveyorAvailability {
	/**
	 * @var integer $id
	 *
	 * @Column(name="id", type="integer", nullable=false)
	 * @Id
	 * @GeneratedValue(strategy="IDENTITY")
	 */
	private $id;
	
	/**
	 * @var datetime $date
	 *
	 * @Column(name="date", type="date", nullable=false)
	 */
	private $date;
	
	/**
	 * @var datetime $createdDate
	 *
	 * @Column(name="created_date", type="datetime", nullable=false)
	 */
	private $createdDate;
	
	/**
	 * @var datetime $updatedDate
	 *
	 * @Column(name="updated_date", type="datetime", nullable=false)
	 */
	private $updatedDate;
	
	/**
	 * @var Surveyor
	 *
	 * @ManyToOne(targetEntity="Surveyor")
	 * @JoinColumns({
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 * })
	 */
	private $user;
	
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
	
	/**
	 * @PrePersist
	 * @PreUpdate
	 */
	public function setUpdateDate() {
		
		if (! $this->id) {
			$this->createdDate = new \DateTime ();
		}
		$this->updatedDate = new \DateTime ();
	
	}
	
	/**
	 * @param \Entities\Entity\Surveyor $user
	 */
	public function setSurveyor($user) {
		$model = $this->getModel ( $user, 'Entities\Entity\Surveyor' );
		if ($model)
			$this->surveyor = $model;
	}
	
	public function setDate($value) {
		
		if ($value)
			$this->date = \DateTime::createFromFormat ( 'd/m/Y', $value );
		
	
	}
	public function getDate() {
		if ($this->date)
			return $this->date->format ( 'd/m/Y' );
		return $this->date;
	}

}