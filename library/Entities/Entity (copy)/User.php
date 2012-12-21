<?php
namespace Entities\Entity;

/**
 * User
 *
 * @Table(name="user")
 * @Entity @HasLifecycleCallbacks
 */
class User {
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
	 * @var string $password
	 *
	 * @Column(name="password", type="string", length=255, nullable=false)
	 */
	private $password;
	
	/**
	 * @var datetime $createdDate
	 *
	 * @Column(name="created_date", type="datetime", nullable=true)
	 */
	private $createdDate;
	
	/**
	 * @var datetime $updatedDate
	 *
	 * @Column(name="updated_date", type="datetime", nullable=true)
	 */
	private $updatedDate;
	
	/**
	 * @var Role
	 *
	 * @ManyToOne(targetEntity="Role")
	 * @JoinColumns({
	 * @JoinColumn(name="role_id", referencedColumnName="id")
	 * })
	 */
	private $role;
	
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
	 * @var Agency
	 *
	 * @ManyToOne(targetEntity="Agency", inversedBy = "users")
	 * @JoinColumns({
	 * @JoinColumn(name="agency_id", referencedColumnName="id")
	 * })
	 */
	private $agency;
	
	
	/**
	 *
	 * @param \Doctrine\Common\Collections\Collection $property
	 *
	 * @OneToOne(targetEntity="Surveyor",mappedBy="user")
	 */
	private $surveyor;
	
	
	/**
	 *
	 * @param \Doctrine\Common\Collections\Collection $property
	 *
	 * @OneToMany(targetEntity="UserFiles",mappedBy="user")
	 */
	private $files;
	
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
	 * @param \Entities\Entity\Role $role
	 */
	public function setRole($role) {
		$model = $this->getModel ( $role, 'Entities\Entity\Role' );
		if ($model)
			$this->role = $model;
	}
	
	public function setPassword($value) {
		$this->password = md5 ( $value );
	}
	
	/**
	 * @param \Entities\Entity\Role $role
	 */
	public function setStatus($status) {
		$model = $this->getModel ( $status, 'Entities\Entity\Status' );
		if ($model)
			$this->status = $model;
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
	public function setAgency($agency) {
		$model = $this->getModel ( $agency, 'Entities\Entity\Agency' );
		if ($model)
			$this->agency = $model;
	}
	
	
	

}