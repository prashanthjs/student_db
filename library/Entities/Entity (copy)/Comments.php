<?php
namespace Entities\Entity;

/**
 * Comments
 *
 * @Table(name="comments")
 * @Entity @HasLifecycleCallbacks
 */
class Comments
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
     * @var string $comment
     *
     * @Column(name="comment", type="string", length=255, nullable=false)
     */
    private $comment;

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
     * @var Survey
     *
     * @ManyToOne(targetEntity="Survey", inversedBy="comments")
     * @JoinColumns({
     * @JoinColumn(name="survey_id", referencedColumnName="id")
     * })
     */
    private $survey;

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

    /**
     * @PrePersist
     * @PreUpdate
     */
    public function setUpdateDate ()
    {
        $this->message = 'updated comment on the survey with an estimation number ';
        if (! $this->id) {
            $this->createdDate = new \DateTime();
            $this->message = 'A comment has been added on the survey with an estimation number ';
        }
        $this->updatedDate = new \DateTime();
    }

    /**
     * @param \Entities\Entity\User $user
     */
    public function setUser ($user)
    {
        $model = $this->getModel($user, 'Entities\Entity\User');
        if ($model)
            $this->user = $model;
    }

    /**
     * @param \Entities\Entity\Survey $survey
     */
    public function setSurvey ($survey)
    {
        $model = $this->getModel($survey, 'Entities\Entity\Survey');
        if ($model)
            $this->survey = $model;
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

    /**
     * @PostPersist
     * @PostUpdate
     */
    public function logSurvey ()
    {
        
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        
        $text = '<span class="message" style="color:' .
         $this->survey->surveyStatus->color . '" >' .
         \Core_Constants::getUser()->name . '(' .
         \Core_Constants::getUser()->email . ') ' . $this->message . "</span>";
        $link = '<a href="/product/survey-individual/view/id/' .
         $this->survey->id . '">' . $this->survey->estimationNumber . '</a>';
        
        $log = new \Entities\Entity\Log();
        $log->user = $this->survey->user;
        $log->text = $text . ' ' . $link;
        $em->persist($log);
        $em->flush();
    
    }
}