<?php

namespace Entities\Entity;

/**
 * PricePlanRedemption
 *
 * @Table(name="price_plan_redemption")
 * @Entity @HasLifecycleCallbacks
 */
class PricePlanRedemption
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
     * @var float $redemptionAmount
     *
     * @Column(name="redemption_amount", type="float", nullable=false)
     */
    private $redemptionAmount;

    /**
     * @var float $redemptionPercentage
     *
     * @Column(name="redemption_percentage", type="float", nullable=false)
     */
    private $redemptionPercentage;

    /**
     * @var PricePlan
     *
     * @OneToOne(targetEntity="PricePlan",  inversedBy="redemption")
     * @JoinColumns({
     *   @JoinColumn(name="price_plan", referencedColumnName="id")
     * })
     */
    private $pricePlan;
    
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
     * @param \Entities\Entity\PricePlan $pricePlan
     */
    public function setPricePlan($pricePlan) {
    	$model = $this->getModel ( $pricePlan, 'Entities\Entity\PricePlan' );
    	if ($model)
    		$this->pricePlan = $model;
    }
    
   
    

}