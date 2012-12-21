<?php

namespace Entities\Entity;

/**
 * Surveyor
 *
 * @Table(name="surveyor")
 * @Entity @HasLifecycleCallbacks
 */
class Surveyor {
	/**
	 * @var integer $id
	 *
	 * @Column(name="id", type="integer", nullable=false)
	 * @Id
	 * @GeneratedValue(strategy="IDENTITY")
	 */
	private $id;
	
	/**
	 * @var string $address1
	 *
	 * @Column(name="address1", type="string", length=255, nullable=false)
	 */
	private $address1;
	
	/**
	 * @var string $address2
	 *
	 * @Column(name="address2", type="string", length=255, nullable=false)
	 */
	private $address2;
	
	/**
	 * @var string $town
	 *
	 * @Column(name="town", type="string", length=255, nullable=false)
	 */
	private $town;
	
	/**
	 * @var string $postcode
	 *
	 * @Column(name="postcode", type="string", length=255, nullable=false)
	 */
	private $postcode;
	
	/**
	 * @var string $mobile
	 *
	 * @Column(name="mobile", type="string", length=255, nullable=false)
	 */
	private $mobile;
	
	/**
	 * @var datetime $visaExpiry
	 *
	 * @Column(name="visa_expiry", type="datetime", nullable=false)
	 */
	private $visaExpiry;
	
	
	
	/**
	 * @var smallint $gender
	 *
	 * @Column(name="gender", type="smallint", nullable=false)
	 */
	private $gender;
	
	/**
	 * @var datetime $dob
	 *
	 * @Column(name="dob", type="datetime", nullable=false)
	 */
	private $dob;
	
	/**
	 * @var datetime $doj
	 *
	 * @Column(name="doj", type="datetime", nullable=false)
	 */
	private $doj;
	
	/**
	 * @var string $niNo
	 *
	 * @Column(name="ni_no", type="string", length=255, nullable=false)
	 */
	private $niNo;
	
	/**
	 * @var string $passportNo
	 *
	 * @Column(name="passport_no", type="string", length=255, nullable=false)
	 */
	private $passportNo;
	
	/**
	 * @var string $nationality
	 *
	 * @Column(name="nationality", type="string", length=255, nullable=false)
	 */
	private $nationality;
	
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
	 * @var JobRole
	 *
	 * @ManyToOne(targetEntity="JobRole")
	 * @JoinColumns({
	 * @JoinColumn(name="job_role_id", referencedColumnName="id")
	 * })
	 */
	private $jobRole;
	
	/**
	 * @var User
	 *
	 * @OneToOne(targetEntity="User", inversedBy="surveyor")
	 * @JoinColumns({
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 * })
	 */
	private $user;

	
	/**
	 * @param \Entities\Entity\JobRole $role
	 */
	public function setJobRole($role) {
		$model = $this->getModel ( $role, 'Entities\Entity\JobRole' );
		if ($model)
			$this->jobRole = $model;
	}
	
	/**
	 * @param \Entities\Entity\JobRole $role
	 */
	public function setUser($role) {
		$model = $this->getModel ( $role, 'Entities\Entity\User' );
		if ($model)
			$this->user = $model;
	}
	
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
	
	public function setDob($value) {
		if ($value)
			$this->dob = \DateTime::createFromFormat ( 'd/m/Y', $value );
	
	}
	
	public function setDoj($value) {
		if ($value)
			$this->doj = \DateTime::createFromFormat ( 'd/m/Y', $value );
	
	}
	public function setVisaExpiry($value) {
		if ($value)
			$this->visaExpiry = \DateTime::createFromFormat ( 'd/m/Y', $value );
	
	}
	public function getDob() {
		if ($this->dob)
			return $this->dob->format ( 'd/m/Y' );
		return $this->dob;
	}
	
	public function getDoj() {
		if ($this->doj)
			return $this->doj->format ( 'd/m/Y' );
		return $this->doj;
	}
	
	public function getVisaExpiry() {
		if ($this->visaExpiry)
			return $this->visaExpiry->format ( 'd/m/Y' );
		return $this->visaExpiry;
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
	
	

}