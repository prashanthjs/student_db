<?php

namespace Entities\Entity;


/**
 * SurveyorAmountPaid
 *
 * @Table(name="surveyor_amount_paid")
 * @Entity @HasLifecycleCallbacks
 */
class SurveyorAmountPaid
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
     * @var float $basic
     *
     * @Column(name="basic", type="float", nullable=false)
     */
    private $basic;

    /**
     * @var float $fines
     *
     * @Column(name="fines", type="float", nullable=false)
     */
    private $fines;

    /**
     * @var float $amountToPaid
     *
     * @Column(name="amount_to_paid", type="float", nullable=false)
     */
    private $amountToPaid;

    /**
     * @var float $surveyorRetaintion
     *
     * @Column(name="surveyor_retaintion", type="float", nullable=true)
     */
    private $surveyorRetaintion;
    
    /**
     * @var float $expenses
     *
     * @Column(name="expenses", type="float", nullable=false)
     */
    private $expenses;
    
    /**
     * @var float $overrides
     *
     * @Column(name="overrides", type="float", nullable=false)
     */
    private $overrides;

    /**
     * @var date $createdDate
     *
     * @Column(name="created_date", type="date", nullable=false)
     */
    private $createdDate;

    /**
     * @var float $currentRetaintion
     *
     * @Column(name="current_retaintion", type="float", nullable=false)
     */
    private $currentRetaintion;

    /**
     * @var float $actualAmount
     *
     * @Column(name="actual_amount", type="float", nullable=false)
     */
    private $actualAmount;

    /**
     * @var integer $week
     *
     * @Column(name="week", type="integer", nullable=false)
     */
    private $week;

    /**
     * @var integer $year
     *
     * @Column(name="year", type="integer", nullable=false)
     */
    private $year;

    /**
     * @var float $dues
     *
     * @Column(name="dues", type="float", nullable=false)
     */
    private $dues;

    /**
     * @var float $vat
     *
     * @Column(name="vat", type="float", nullable=false)
     */
    private $vat;

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
     * @var Status
     *
     * @ManyToOne(targetEntity="Status")
     * @JoinColumns({
     * @JoinColumn(name="status_id", referencedColumnName="id")
     * })
     */
    private $status;

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
     * @param \Entities\Entity\Status $status
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
    	
    
    }
}