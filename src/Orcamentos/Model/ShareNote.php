<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="ShareNote")
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
     * @var text
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

    public function getShareNotesCollection()
    {
        return $this->shareNotesCollection;
    }
    
    public function setShareNotesCollection($shareNotesCollection)
    {
        return $this->shareNotesCollection = $shareNotesCollection;
    }
}
