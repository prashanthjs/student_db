<?php
namespace Entities\Entity;

/**
 * AdminLog
 *
 * @Table(name="admin_log")
 * @Entity @HasLifecycleCallbacks
 */
class AdminLog
{
    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $message
     *
     * @Column(name="message", type="string", length=255, nullable=false)
     */
    private $message;

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
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var Survey
     *
     * @ManyToOne(targetEntity="Survey", inversedBy="logs")
     * @JoinColumns({
     *   @JoinColumn(name="survey_id", referencedColumnName="id")
     * })
     */
    private $survey;
    
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
     * @param \Entities\Entity\User $user
     */
    public function setUser($value) {
    	$model = $this->getModel ( $value, 'Entities\Entity\User' );
    	if ($model)
    		$this->user = $model;
    	else{
    		$this->user = null;
    	}
    }
    
    /**
     * @param \Entities\Entity\Survey $survey
     */
    public function setSurvey($value) {
    	$model = $this->getModel ( $value, 'Entities\Entity\Survey' );
    	if ($model)
    		$this->survey = $model;
    	else{
    		$this->survey = null;
    	}
    }

}