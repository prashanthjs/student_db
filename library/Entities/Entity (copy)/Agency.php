<?php
namespace Entities\Entity;
/**
 * Agency
 *
 * @Table(name="agency")
 * @Entity @HasLifecycleCallbacks
 */
class Agency {
	/**
	 * @var integer $id
	 *
	 * @Column(name="id", type="integer", nullable=false)
	 * @Id
	 * @GeneratedValue(strategy="IDENTITY")
	 */
	private $id;
	
	/**
	 * @var string $name
	 *
	 * @Column(name="name", type="string", length=255, nullable=false)
	 */
	private $name;
	
	/**
	 * @var string $email
	 *
	 * @Column(name="email", type="string", length=255, nullable=false)
	 */
	private $email;
	
	/**
	 * @var string $registrationNumber
	 *
	 * @Column(name="registration_number", type="string", length=255, nullable=false)
	 */
	private $registrationNumber;
	
	/**
	 * @var string $address1
	 *
	 * @Column(name="address1", type="string", length=255, nullable=true)
	 */
	private $address1;
	
	/**
	 * @var string $address2
	 *
	 * @Column(name="address2", type="string", length=255, nullable=true)
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
	 *namespace Entities\Entity;
	 * @Column(name="mobile", type="string", length=255, nullable=false)
	 */
	private $mobile;
	
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
	 * @var Agency
	 *
	 * @ManyToOne(targetEntity="Agency")
	 * @JoinColumns({
	 * @JoinColumn(name="parent_id", referencedColumnName="id")
	 * })
	 */
	private $parent;
	
	/**
	 * @var Status
	 *
	 * @ManyToOne(targetEntity="Status")
	 * @JoinColumns({
	 * @JoinColumn(name="status_id", referencedColumnName="id")
	 * })
	 */
	private $status;
	
	/**
	 *
	 * @param \Doctrine\Common\Collections\Collection $property
	 *
	 * @OneToMany(targetEntity="User",mappedBy="agency")
	 */
	private $users;
	
	
	
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
	 * @param \Entities\Entity\Agency $agency
	 */
	public function setParent($agency) {
		$model = $this->getModel ( $agency, 'Entities\Entity\Agency' );
		if ($model)
			$this->parent = $model;
		else{
			$this->parent = null;
		}
	}
	
	/**
	 * @param \Entities\Entity\Status $status
	 */
	public function setStatus($status) {
		$model = $this->getModel ( $status, 'Entities\Entity\Status' );
		if ($model)
			$this->status = $model;
	}
	
	
}