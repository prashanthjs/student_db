<?php
namespace Entities\Entity;


/**
 * AgencyDues
 *
 * @Table(name="agency_dues")
 * @Entity @HasLifecycleCallbacks
 */
class AgencyDues
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
     *   @JoinColumn(name="agency_id", referencedColumnName="id")
     * })
     */
    private $agency;

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