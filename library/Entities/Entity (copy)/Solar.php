<?php

namespace Entities\Entity;

/**
 * Solar
 *
 * @Table(name="solar")
 * @Entity @HasLifecycleCallbacks
 */
class Solar
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
     * @var string $address1
     *
     * @Column(name="address1", type="string", length=255, nullable=false)
     */
    private $address1;

    /**
     * @var string $address2
     *
     * @Column(name="address2", type="string", length=255, nullable=false)
     */
    private $address2;

    /**
     * @var datetime $dos
     *
     * @Column(name="dos", type="datetime", nullable=false)
     */
    private $dos;

    /**
     * @var integer $userId
     *
     * @Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;
    
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
    
    public function getModel( $id, $model){
    	if (!is_object( $id )) {
    		$em = \Zend_Registry::get ( 'doctrine' )->getEntityManager ();
    		$model = $em->find($model, $id);
    		if($model){
    			return $model;
    		}
    		else{
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
    
    	if(!$this->id){
    		$this->createdDate = new \DateTime();
    	}
    	$this->updatedDate = new \DateTime();
    
    }

}