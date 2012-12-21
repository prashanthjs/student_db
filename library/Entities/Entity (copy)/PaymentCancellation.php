<?php


namespace Entities\Entity;

/**
 * PaymentCancellation
 *
 * @Table(name="payment_cancellation")
 * @Entity @HasLifecycleCallbacks
 */
class PaymentCancellation
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
     * @var date $effectiveDate
     *
     * @Column(name="effective_date", type="date", nullable=false)
     */
    private $effectiveDate;

    /**
     * @var text $note
     *
     * @Column(name="note", type="text", nullable=false)
     */
    private $note;

    /**
     * @var float $fee
     *
     * @Column(name="fee", type="float", nullable=false)
     */
    private $fee;

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
     * @var Survey
     *
     * @ManyToOne(targetEntity="Survey")
     * @JoinColumns({
     *   @JoinColumn(name="survey_id", referencedColumnName="id")
     * })
     */
    private $survey;
    
    /**
     * @var string $estimationNumber
     *
     * @Column(name="estimation_number", type="string", length=255, nullable=true)
     */
    private $estimationNumber;
    
    public function __get ($property)
    {
    
        if (\method_exists($this, "get" .\ucfirst($property))) {
            $method_name = "get" .\ucfirst($property);
            return $this->$method_name();
        } else
            return $this->$property;
    }
    
    public function __set ($property, $value)
    {
        if (\method_exists($this, "set" .\ucfirst($property))) {
            $method_name = "set" .\ucfirst($property);
    
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
    
    public function setSurvey ($survey)
    {
        $model = $this->getModel($survey, 'Entities\Entity\Survey');
        if ($model)
            $this->survey = $model;
    }
    
    public function setEffectiveDate ($value)
    {
        if ($value) {
            if (is_a($value, 'DateTime')) {
                $this->effectiveDate = $value;
                return;
            }
            $this->effectiveDate = \DateTime::createFromFormat('d/m/Y', $value);
        }
    
    }

}