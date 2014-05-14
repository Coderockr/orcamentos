<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Company")
 */
class Company
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
     * @ORM\Column(type="float", nullable=false )
     *
     * @var float
     */
    private $taxes;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $site;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string
     */
    private $logotype;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $responsable;

    /**
     * @ORM\OneToMany(targetEntity="Client", mappedBy="company", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * 
     * @var Doctrine\Common\Collections\Collection
     */
    protected $clientCollection;

    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="company", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * 
     * @var Doctrine\Common\Collections\Collection
     */
    protected $projectCollection;  

    /**
     * @ORM\OneToMany(targetEntity="Resource", mappedBy="company", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * 
     * @var Doctrine\Common\Collections\Collection
     */
    protected $resourceCollection;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="company", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * 
     * @var Doctrine\Common\Collections\Collection
     */
    protected $userCollection;

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

    public function getTaxes()
    {
        return $this->taxes;
    }
    
    public function setTaxes($taxes)
    {
        return $this->taxes = $taxes;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }
    
    public function setTelephone($telephone)
    {
        return $this->telephone = $telephone;
    }

    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        return $this->email = $email;
    }
    
    public function getSite()
    {
        return $this->site;
    }
    
    public function setSite($site)
    {
        return $this->site = $site;
    }
    
    public function getLogotype()
    {
        return $this->logotype;
    }
    
    public function setLogotype($logotype)
    {
        return $this->logotype = $logotype;
    }
    
    public function getResponsable()
    {
        return $this->responsable;
    }
    
    public function setResponsable($responsable)
    {

        return $this->responsable = $responsable;
    }

    public function getClientCollection()
    {
        return $this->clientCollection;
    }
    
    public function setClientCollection($clientCollection)
    {
        return $this->clientCollection = $clientCollection;
    }
    
    public function getUserCollection()
    {
        return $this->userCollection;
    }
    
    public function setUserCollection($userCollection)
    {
        return $this->userCollection = $userCollection;
    }

    public function getProjectCollection()
    {
        return $this->projectCollection;
    }
    
    public function setProjectCollection($projectCollection)
    {
        return $this->projectCollection = $projectCollection;
    }

    public function getResourceCollection()
    {
        return $this->resourceCollection;
    }
    
    public function setResourceCollection($resourceCollection)
    {
        return $this->resourceCollection = $resourceCollection;
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
