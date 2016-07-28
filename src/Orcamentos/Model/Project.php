<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Project")
 */
class Project extends Entity
{

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var text
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

    /**
     * @ORM\OneToMany(targetEntity="Requisite", mappedBy="project", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     *
     * @var Doctrine\Common\Collections\Collection
     */
    protected $requisitesCollection;

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
     * @return Doctrine\Common\Collections\Collection
     */
    public function getRequisitesCollection()
    {
        return $this->requisitesCollection;
    }

    /**
     * @param Doctrine\Common\Collections\Collection $requisiteCollection
     */
    public function setRequisitesCollection($requisitesCollection)
    {
        $this->requisitesCollection = $requisitesCollection;
    }

}
