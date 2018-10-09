<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Orcamentos\Filter\CNPJMask;

/**
 * @ORM\Entity
 * @ORM\Table(name="Client")
 */
class Client extends Entity
{

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=14)
     *
     * @var string
     */
    private $cnpj;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $corporateName;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $logotype;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $telephone;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="clientCollection",  cascade={"persist", "merge", "refresh"})
     * 
     * @var Company
     */
    protected $company;

    /**
     * @ORM\Column(type="string")
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
        $this->setCreated(date("Y-m-d H:i:s"));
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
        $cnpjMask = new CNPJMask();
        return $cnpjMask->applyMask($this->cnpj);
    }
    
    public function setCnpj($cnpj)
    {
        $cnpjMask = new CNPJMask();
        $cnpj = $cnpjMask->removeMask($cnpj);

        return $this->cnpj = $cnpj;
    }

    public function getCorporateName()
    {
        return $this->corporateName;
    }
    
    public function setCorporateName($corporateName)
    {
        $this->corporateName = $corporateName;
        return $this;
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
}
