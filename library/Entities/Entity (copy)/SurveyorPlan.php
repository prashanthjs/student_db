<?php
namespace Entities\Entity;
/**
 * SurveyorPlan
 *
 * @Table(name="surveyor_plan")
 * @Entity @HasLifecycleCallbacks
 */
class SurveyorPlan
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
     * @var datetime $startDate
     *
     * @Column(name="start_date", type="datetime", nullable=false)
     */
    private $startDate;

    /**
     * @var Surveyor
     *
     * @ManyToOne(targetEntity="Surveyor")
     * @JoinColumns({
     *   @JoinColumn(name="surveyor_id", referencedColumnName="id")
     * })
     */
    private $surveyor;

    /**
     * @var PricePlan
     *
     * @ManyToOne(targetEntity="PricePlan")
     * @JoinColumns({
     *   @JoinColumn(name="price_plan_id", referencedColumnName="id")
     * })
     */
    private $pricePlan;
    
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
    public function setSurveyor($agency) {
    	$model = $this->getModel ( $agency, 'Entities\Entity\Surveyor' );
    	if ($model)
    		$this->surveyor = $model;
    }
    
    
    
    /**
     * @PrePersist
     * @PreUpdate
     */
    public function setStartDate() {
    	$this->startDate = new \DateTime ();
    }
    
    
}