<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="PrivateNote")
 */
class PrivateNote extends Entity
{

    /**
     * @ORM\ManyToOne(targetEntity="Project", cascade={"persist", "merge", "refresh"}, inversedBy="privateNotesCollection")
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

    public function getNote()
    {
        return $this->note;
    }
    
    public function setNote($note)
    {
        return $this->note = $note;
    }
}
