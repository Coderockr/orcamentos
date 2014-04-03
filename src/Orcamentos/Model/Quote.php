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
     * @ORM\Column(type="string", length=100, unique=true)
     *
     * @var string
     */
    private $insideNotes;

    /**
     * @ORM\Column(type="string", length=150)
     *
     * @var string
     */
    private $clientNotes;

    /**
     * @ORM\ManyToOne(targetEntity="Project", cascade={"persist", "merge", "refresh"})
     * 
     * @var Project
     */
    protected $project;

    /**
     * @ORM\OneToMany(targetEntity="Resource", mappedBy="quote", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * 
     * @var Doctrine\Common\Collections\Collection
     */
    protected $resourceCollection;

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
    
    public function getInsideNotes()
    {
        return $this->insideNotes;
    }
    
    public function setInsideNotes($insideNotes)
    {
        return $this->insideNotes = $insideNotes;
    }

    public function getClientNotes()
    {
        return $this->clientNotes;
    }
    
    public function setClientNotes($clientNotes)
    {
        return $this->clientNotes = $clientNotes;
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
