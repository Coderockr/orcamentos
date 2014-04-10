<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="PrivateNote")
 */
class PrivateNote
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     * @var datetime
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @var datetime
     */
    protected $updated;

    /**
     * @ORM\ManyToOne(targetEntity="Project", cascade={"persist", "merge", "refresh"})
     * 
     * @var Project
     */
    protected $project; 

    /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist", "merge", "refresh"})
     * 
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var text
     */
    private $note;

    public function __construct()
    {
        $this->setCreated(date("Y-m-d H:i:s"));
    }
   
    public function getProject()
    {
        return $this->project;
    }
    
    public function setProject($project)
    {
        return $this->project = $project;
    }   

    public function getUser()
    {
        return $this->user;
    }
    
    public function setUser($user)
    {
        return $this->user = $user;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function getCreated()
    {
        return $this->created->format('Y-m-d H:i:s');
    }
    
    public function setCreated($created)
    {
        $this->created = \DateTime::createFromFormat('Y-m-d H:i:s', $created);    
    }

    public function getUpdated()
    {
        return $this->updated;
    }
    
    public function setUpdated($updated)
    {
        $this->updated = \DateTime::createFromFormat('Y-m-d H:i:s', $updated);
    }

}
