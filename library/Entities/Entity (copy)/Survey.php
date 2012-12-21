<?php

namespace Entities\Entity;

/**
 * Survey
 *
 * @Table(name="survey")
 * @Entity @HasLifecycleCallbacks
 */
class Survey
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
     * @var string $hno
     *
     * @Column(name="hno", type="string", length=255, nullable=false)
     */
    private $hno;

    /**
     * @var string $postcode
     *
     * @Column(name="postcode", type="string", length=255, nullable=false)
     */
    private $postcode;

    /**
     * @var string $address
     *
     * @Column(name="address", type="string", length=255, nullable=false)
     */
    private $address;

    /**
     * @var string $benefit
     *
     * @Column(name="benefit", type="string", length=255, nullable=true)
     */
    private $benefit;

    /**
     * @var string $telNumber
     *
     * @Column(name="tel_number", type="string", length=255, nullable=true)
     */
    private $telNumber;

    /**
     * @var string $mobileNumber
     *
     * @Column(name="mobile_number", type="string", length=255, nullable=true)
     */
    private $mobileNumber;

    /**
     * @var datetime $submittedDate
     *
     * @Column(name="submitted_date", type="datetime", nullable=false)
     */
    private $submittedDate;

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
     * @var date $paymentDate
     *
     * @Column(name="payment_date", type="date", nullable=false)
     */
    private $paymentDate;

    /**
     * @var SurveyStatus
     *
     * @ManyToOne(targetEntity="SurveyStatus")
     * @JoinColumns({
     * @JoinColumn(name="survey_status_id", referencedColumnName="id")
     * })
     */
    private $surveyStatus;

    /**
     * @var Priority
     *
     * @ManyToOne(targetEntity="Priority")
     * @JoinColumns({
     * @JoinColumn(name="priority_id", referencedColumnName="id")
     * })
     */
    private $priority;

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
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     * @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="SurveyImages",mappedBy="survey")
     */
    private $images;

    /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Comments",mappedBy="survey")
     */
    private $comments;

    /**
     * @var string $estimationNumber
     *
     * @Column(name="estimation_number", type="string", length=255, nullable=true)
     */
    private $estimationNumber;

    /**
     * @var date $installationDate
     *
     * @Column(name="installation_date", type="date", nullable=true)
     */
    private $installationDate;

    /**
     * @var date $gtgDate
     *
     * @Column(name="gtg_date", type="date", nullable=true)
     */
    private $gtgDate;

    /**
     * @var date $sendDate
     *
     * @Column(name="send_date", type="date", nullable=true)
     */
    private $sendDate;

    /**
     * @var SurveyLlHh
     *
     * @ManyToOne(targetEntity="SurveyLlHh")
     * @JoinColumns({
     * @JoinColumn(name="survey_ll_hh_id", referencedColumnName="id")
     * })
     */
    private $surveyLlHh;

    /**
     * @var Installer
     *
     * @ManyToOne(targetEntity="Installer")
     * @JoinColumns({
     * @JoinColumn(name="installer_id", referencedColumnName="id")
     * })
     */
    private $installer;

    /**
     * @var string $propertyType
     *
     * @Column(name="property_type", type="string", length=255, nullable=true)
     */
    private $propertyType;

    /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="AdminLog",mappedBy="survey")
     */
    private $logs;
    
    /**
     * @var PaymentStatus
     *
     * @ManyToOne(targetEntity="PaymentStatus")
     * @JoinColumns({
     *   @JoinColumn(name="payment_status_id", referencedColumnName="id")
     * })
     */
    private $paymentStatus;
    
    
    /**
     * @var JobStatus
     *
     * @ManyToOne(targetEntity="JobStatus")
     * @JoinColumns({
     *   @JoinColumn(name="job_status_id", referencedColumnName="id")
     * })
     */
    private $jobStatus;

    public $message;

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
        if(!$this->message){
        $this->message = 'Updated the survey';
        }
        if (! $this->id) {
            $this->createdDate = new \DateTime();
            $this->message = 'Survey has been created';
        }
        $this->updatedDate = new \DateTime();
    
    }

    /**
     * @PrePersist
     * @PreUpdate
     */
    public function setGoodToGoDate ()
    {
        
        if ($this->surveyStatus->id == \Core_Constants::SURVEY_GOOD_TO_GO) {
            
            if (! $this->gtgDate) {
                $this->gtgDate = new \DateTime();
                if (! $this->paymentDate) {
                    $date = new \DateTime();
                    $date->setTimestamp(strtotime("+4 week friday"));
                    $this->paymentDate = $date;
                }
            }
        }
    
    }

    /**
     * @PostPersist
     * @PostUpdate
     */
    public function logSurvey ()
    {
        
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $admin = new \Entities\Entity\AdminLog();
        $admin->user = \Core_Constants::getUser()->id;
        $admin->survey = $this->id;
        $admin->message = $this->message;
        $em->persist($admin);
        $em->flush();
        
        $text = '<span class="message" style="color:' .
         $this->surveyStatus->color . '" >' . \Core_Constants::getUser()->name .
         '(' . \Core_Constants::getUser()->email . ') ' . $this->message .
         "</span>";
        $link = '<a href="/product/survey-individual/view/id/' . $this->id . '">' .
         $this->estimationNumber . '</a>';
        
        $log = new \Entities\Entity\Log();
        $log->user = $this->user;
        $log->text = $text . ' ' . $link;
        $em->persist($log);
        $em->flush();
    
    }
    
    

    /**
     * @param \Entities\Entity\Agency $agency
     */
    public function setSurveyStatus ($status)
    {
        $model = $this->getModel($status, 'Entities\Entity\SurveyStatus');
        if ($model)
            $this->surveyStatus = $model;
    }

    /**
     * @param \Entities\Entity\Agency $agency
     */
    public function setPriority ($status)
    {
        $model = $this->getModel($status, 'Entities\Entity\Priority');
        if ($model)
            $this->priority = $model;
    }

    /**
     * @param \Entities\Entity\Agency $agency
     */
    public function setInstallation ($status)
    {
        $model = $this->getModel($status, 'Entities\Entity\Installation');
        if ($model)
            $this->installation = $model;
    }

    /**
     * @param \Entities\Entity\Agency $agency
     */
    public function setInsulationTime ($status)
    {
        $model = $this->getModel($status, 'Entities\Entity\InsulationTime');
        if ($model)
            $this->insulationTime = $model;
    }

    /**
     * @param \Entities\Entity\SurveyLlHh $agency
     */
    public function setSurveyLlHh ($status)
    {
        $model = $this->getModel($status, 'Entities\Entity\SurveyLlHh');
        if ($model)
            $this->surveyLlHh = $model;
    }

    /**
     * @param \Entities\Entity\Agency $agency
     */
    public function setUser ($status)
    {
        $model = $this->getModel($status, 'Entities\Entity\User');
        if ($model)
            $this->user = $model;
    }

    /**
     * @param \Entities\Entity\Installer $agency
     */
    public function setInstaller ($installer)
    {
        $model = $this->getModel($installer, 'Entities\Entity\Installer');
        if ($model)
            $this->installer = $model;
    }

    
    /**
     * @param \Entities\Entity\Installer $agency
     */
    public function setJobStatus ($job)
    {
        $model = $this->getModel($job, 'Entities\Entity\JobStatus');
        if ($model)
            $this->jobStatus = $model;
    }
    
    
    /**
     * @param \Entities\Entity\Installer $agency
     */
    public function setPaymentStatus ($paymentStatus)
    {
        $model = $this->getModel($paymentStatus, 'Entities\Entity\PaymentStatus');
        if ($model)
            $this->paymentStatus = $model;
    }
    
    public function setSubmittedDate ($value)
    {
        if ($value) {
            if (is_a($value, 'DateTime')) {
                $this->submittedDate = $value;
                return;
            }
            $this->submittedDate = \DateTime::createFromFormat('d/m/Y', $value);
        }
    
    }

    public function setInstallationDate ($value)
    {
        if ($value) {
            if (is_a($value, 'DateTime')) {
                $this->installationDate = $value;
                return;
            }
            $this->installationDate = \DateTime::createFromFormat('d/m/Y', 
            $value);
        }
    
    }

    public function setPaymentDate ($value)
    {
        if ($value) {
            if (is_a($value, 'DateTime')) {
                $this->paymentDate = $value;
                return;
            }
            $this->paymentDate = \DateTime::createFromFormat('d/m/Y', $value);
        }
    
    }

    public function setSendDate ($value)
    {
        if ($value) {
            if (is_a($value, 'DateTime')) {
                $this->sendDate = $value;
                return;
            }
            $this->sendDate = \DateTime::createFromFormat('d/m/Y', $value);
        }
    
    }

    public function getSubmittedDate ()
    {
        if ($this->submittedDate)
            return $this->submittedDate->format('d/m/Y');
    
    }

    public function getPaymentDate ()
    {
        if ($this->paymentDate)
            return $this->paymentDate->format('d/m/Y');
    
    }

    public function getInstallationDate ()
    {
        if ($this->installationDate)
            return $this->installationDate->format('d/m/Y');
    
    }

    public function getCreatedDate ()
    {
        if ($this->createdDate)
            return $this->createdDate->format('d/m/Y');
    
    }

    public function getUpdatedDate ()
    {
        if ($this->updatedDate)
            return $this->updatedDate->format('d/m/Y');
    
    }

}
