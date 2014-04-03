<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="Client")
 */
class Client
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
     * @ORM\Column(type="string", length=14, unique=true)
     *
     * @var string
     */
    private $cnpj;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $logotype;

    /**
     * @ORM\Column(type="string", length=13, nullable=true)
     *
     * @var string
     */
    private $telephone;

    /**
     * @ORM\ManyToOne(targetEntity="Company", cascade={"persist", "merge", "refresh"})
     * 
     * @var Company
     */
    protected $company;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $responsable;
    
    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="client", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * 
     * @var Doctrine\Common\Collections\Collection
     */
    protected $projectCollection;

    public function __construct()
    {
        $this->setCreated(new DateTime());
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        return $this->name = filter_var($name, FILTER_SANITIZE_STRING);
    }
    
    public function getCnpj()
    {
        return $this->cnpj;
    }
    
    public function setCnpj($cnpj)
    {
        return $this->cnpj = $cnpj;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
    	if (FALSE === filter_var($email, FILTER_VALIDATE_EMAIL)) {
    		throw new \InvalidArgumentException('INVALID EMAIL');
    	}
        return $this->email = $email;
    }
    
      public function getLogotype()
    {
        return $this->logotype;
    }
    
    public function setLogotype($logotype)
    {
        return $this->logotype = $logotype;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }
    
    public function setTelephone($telephone)
    {
        return $this->telephone = $telephone;
    }

    public function getCompany()
    {
        return $this->company;
    }
    
    public function setCompany($company)
    {
        return $this->company = $company;
    }

    public function getProjectCollection()
    {
        return $this->projectCollection;
    }
    
    public function setProjectCollection($projectCollection)
    {
        return $this->projectCollection = $projectCollection;
    }
    
    public function getResponsable()
    {
        return $this->responsable;
    }
    
    public function setResponsable($responsable)
    {
        return $this->responsable = $responsable;
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
