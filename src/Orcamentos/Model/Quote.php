<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Quote")
 */
class Quote
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
     * @ORM\Column(type="string", length=150)
     *
     * @var string
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=150)
     *
     * @var string
     */
    private $shareUrl;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $status;

    /**
     * @ORM\Column(type="text")
     *
     * @var text
     */
    private $privateNotes;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="quoteCollection", cascade={"persist", "merge", "refresh"})
     * 
     * @var Project
     */
    protected $project;

    public function __construct()
    {
        $this->setCreated(date('Y-m-d H:i:s'));
    }

    public function getVersion()
    {
        return $this->version;
    }
    
    public function setVersion($version)
    {
        return $this->version = $version;
    }
    
    public function getPrivateNotes()
    {
        return $this->privateNotes;
    }
    
    public function setPrivateNotes($privateNotes)
    {
        return $this->privateNotes = $privateNotes;
    } 

    public function getShareUrl()
    {
        return $this->shareUrl;
    }
    
    public function setShareUrl($shareUrl)
    {
        return $this->shareUrl = $shareUrl;
    }

    public function getStatus()
    {
        return $this->status;
    }
    
    public function setStatus($status)
    {
        return $this->status = $status;
    }    

    public function getProject()
    {
        return $this->project;
    }
    
    public function setProject($project)
    {
        return $this->project = $project;
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
