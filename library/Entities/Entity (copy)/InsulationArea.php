<?php

namespace Entities\Entity;

/**
 * InsulationArea
 *
 * @Table(name="insulation_area")
 * @Entity @HasLifecycleCallbacks
 */
class InsulationArea
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
     * @var Installation
     *
     * @ManyToOne(targetEntity="Installation")
     * @JoinColumns({
     * @JoinColumn(name="installation_id", referencedColumnName="id")
     * })
     */
    private $installation;

    /**
     * @var InsulationTime
     *
     * @ManyToOne(targetEntity="InsulationTime")
     * @JoinColumns({
     * @JoinColumn(name="insulation_time_id", referencedColumnName="id")
     * })
     */
    private $insulationTime;

    public function __get ($property)
    {
        
        if (\method_exists($this, "get" . \ucfirst($property))) {
            $method_name = "get" . \ucfirst($property);
            return $this->$method_name();
        } else
            return $this->$property;
    }

    public function __set ($property, $value)
    {
        if (\method_exists($this, "set" . \ucfirst($property))) {
            $method_name = "set" . \ucfirst($property);
            
            $this->$method_name($value);
        } else
            $this->$property = $value;
    }

    public function getModel ($id, $model)
    {
        if (! is_object($id)) {
            $em = \Zend_Registry::get('doctrine')->getEntityManager();
            $model = $em->find($model, $id);
            if ($model) {
                return $model;
            } else {
                return false;
            }
        } else {
            return $id;
        }
    }

    public function setInsulationTime ($status)
    {
        $model = $this->getModel($status, 'Entities\Entity\InsulationTime');
        if ($model)
            $this->insulationTime = $model;
    }

    /**
     * @PrePersist
     * @PreUpdate
     */
    public function setUpdateDate ()
    {
        
        if (! $this->id) {
            $this->createdDate = new \DateTime();
        }
        $this->updatedDate = new \DateTime();
    
    }

}