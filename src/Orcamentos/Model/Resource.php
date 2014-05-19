<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Resource")
 */
class Resource extends Entity
{
    /**
     * @ORM\Column(type="string", length=150)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    private $cost;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="resourceCollection", cascade={"persist", "merge", "refresh"})
     * 
     * @var Company
     */
    protected $company;

    /**
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="resourceCollection", cascade={"persist", "merge", "refresh"})
     * 
     * @var Type
     */
    protected $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var integer
     */
    private $equipmentLife;

    /**
     * @ORM\OneToMany(targetEntity="ResourceQuote", mappedBy="resource", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * 
     * @var Doctrine\Common\Collections\Collection
     */
    protected $resourceQuoteCollection;

    public function getEquipmentLife()
    {
        return $this->equipmentLife;
    }
    
    public function setEquipmentLife($equipmentLife)
    {
        return $this->equipmentLife = $equipmentLife;
    }
    
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
    
    public function getCost()
    {
        return $this->cost;
    }
    
    public function setCost($cost)
    {
        return $this->cost = $cost;
    }
        
    public function getCompany()
    {
        return $this->company;
    }
    
    public function setCompany($company)
    {
        return $this->company = $company;
    }
    
    public function getPrivateNotes()
    {
        return $this->privateNotes;
    }
    
    public function setPrivateNotes($privateNotes)
    {
        return $this->privateNotes = $privateNotes;
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

    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        return $this->type = $type;
    }

    public function getResourceQuoteCollection()
    {
        return $this->resourceQuoteCollection;
    }
    
    public function setResourceQuoteCollection($resourceQuoteCollection)
    {
        return $this->resourceQuoteCollection = $resourceQuoteCollection;
    }
}
