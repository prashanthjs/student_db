<?php



/**
 * SurveyImages
 *
 * @Table(name="survey_images")
 * @Entity
 */
class SurveyImages
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
     * @var string $image
     *
     * @Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

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

}