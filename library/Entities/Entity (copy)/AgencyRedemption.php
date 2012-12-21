<?php

namespace Entities\Entity;

/**
 * AgencyRedemption
 *
 * @Table(name="agency_redemption")
 * @Entity @HasLifecycleCallbacks
 */
class AgencyRedemption
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
     * @Column(name="amount", type="float", nullable=true)
     */
    private $amount;

    /**
     * @var datetime $date
     *
     * @Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var Agency
     *
     * @ManyToOne(targetEntity="Agency")
     * @JoinColumns({
     *   @JoinColumn(name="agency_id", referencedColumnName="id")
     * })
     */
    private $agency;

    /**
     * @var Survey
     *
     * @ManyToOne(targetEntity="Survey")
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
    		$this->date = new \DateTime ();
    	}
    
    
    }

}