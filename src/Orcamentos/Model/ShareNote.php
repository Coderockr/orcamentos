<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="ShareNote")
 */
class ShareNote
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
