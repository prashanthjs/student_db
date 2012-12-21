<?php
namespace Entities\Entity;



/**
 * SurveyorRetaintionTrack
 *
 * @Table(name="surveyor_retaintion_track")
 * @Entity
 * @HasLifecycleCallbacks
 */
class SurveyorRetaintionTrack
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
     * @var float $amount
     *
     * @Column(name="amount", type="float", nullable=false)
     */
    private $amount;

    /**
     * @var date $createdDate
     *
     * @Column(name="created_date", type="date", nullable=false)
     */
    private $createdDate;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;
    
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
    
    
    }
    
    /**
     * @param \Entities\Entity\Agency $agency
     */
    public function setUser($status) {
    	$model = $this->getModel ( $status, 'Entities\Entity\User' );
    	if ($model)
    		$this->user = $model;
    }

}