<?php


namespace Entities\Entity;


/**
 * PricePlan
 *
 * @Table(name="price_plan")
 * @Entity @HasLifecycleCallbacks
 */
class PricePlan
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
     * @var string $planName
     *
     * @Column(name="plan_name", type="string", length=255, nullable=false)
     */
    private $planName;

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
     * @var integer $vat
     *
     * @Column(name="vat", type="float", nullable=true)
     * 
     */
    private $vat;

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
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToOne(targetEntity="PricePlanRedemption",mappedBy="pricePlan")
     */
    private $redemption;
    
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
    public function setAgency($agency) {
    	$model = $this->getModel ( $agency, 'Entities\Entity\Agency' );
    	if ($model)
    		$this->agency = $model;
    }
    
    

}