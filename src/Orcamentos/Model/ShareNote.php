<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sharenote")
 */
class ShareNote extends Entity
{
    /**
     * @ORM\ManyToOne(targetEntity="Share", inversedBy="shareNotesCollection", cascade={"persist", "merge", "refresh"})
     * 
     * @var Share
     */
    protected $share; 

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    private $note;

    public function __construct()
    {
        $this->setCreated(date("Y-m-d H:i:s"));
    }
   
    public function getShare()
    {
        return $this->share;
    }
    
    public function setShare($share)
    {
        return $this->share = $share;
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
