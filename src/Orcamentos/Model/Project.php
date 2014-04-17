<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Project")
 */
class Project
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
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $tags;
    
    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="projectCollection", cascade={"persist", "merge", "refresh"})
     * 
     * @var Client
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="projectCollection",  cascade={"persist", "merge", "refresh"})
     * 
     * @var Company
     */
    protected $company;

    /**
     * @ORM\OneToMany(targetEntity="Quote", mappedBy="project", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * 
     * @var Doctrine\Common\Collections\Collection
     */
    protected $quoteCollection;

    /**
     * @ORM\OneToMany(targetEntity="PrivateNote", mappedBy="project", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * @ORM\OrderBy({"created" = "DESC"})
     * @var Doctrine\Common\Collections\Collection
     */
    protected $privateNotesCollection;

    public function __construct()
    {
        $this->setCreated(date('Y-m-d H:i:s'));
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        return $this->name = filter_var($name, FILTER_SANITIZE_STRING);
    }
    
    public function getPrivateNotesCollection()
    {
        return $this->privateNotesCollection;
    }
    
    public function setPrivateNotesCollection($privateNotesCollection)
    {
        return $this->privateNotesCollection = $privateNotesCollection;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($description)
    {
        return $this->description = $description;
    }
    
      public function getClientNotes()
    {
        return $this->clientNotes;
    }
    
    public function setClientNotes($clientNotes)
    {
        return $this->clientNotes = $clientNotes;
    }

    public function getTags()
    {
        return $this->tags;
    }
    
    public function setTags($tags)
    {
        return $this->tags = $tags;
    }

    public function getClient()
    {
        return $this->client;
    }
    
    public function setClient($client)
    {
        return $this->client = $client;
    }

    public function getCompany()
    {
        return $this->company;
    }
    
    public function setCompany($company)
    {
        return $this->company = $company;
    }

    public function getQuoteCollection()
    {
        return $this->quoteCollection;
    }
    
    public function setQuoteCollection($quoteCollection)
    {
        return $this->quoteCollection = $quoteCollection;
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
