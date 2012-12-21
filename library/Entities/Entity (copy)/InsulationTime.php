<?php

namespace Entities\Entity;

/**
 * InsulationTime
 *
 * @Table(name="insulation_time")
 * @Entity @HasLifecycleCallbacks
 */
class InsulationTime
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
     * @var string $name
     *
     * @Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer $max
     *
     * @Column(name="max", type="integer", nullable=true)
     */
    private $max;

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