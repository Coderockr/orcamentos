<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Company")
 */
class Company extends Entity
{
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
    private $city;

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
     * @OrderBy({"name" = "ASC"})
     * @var Doctrine\Common\Collections\Collection
     */
    protected $clientCollection;

    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="company", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * @OrderBy({"name" = "ASC"})
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
     * @OrderBy({"name" = "ASC"})
     * 
     * @var Doctrine\Common\Collections\Collection
     */
    protected $userCollection;

    /**
     * @ORM\ManyToOne(targetEntity="Plan", inversedBy="companyCollection", cascade={"persist", "merge", "refresh"})
     * 
     * @var Plan
     */
    protected $plan;

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

    public function getCity()
    {
        return $this->city;
    }
    
    public function setCity($city)
    {
        return $this->city = $city;
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

    public function getPlan()
    {
        return $this->plan;
    }
    
    public function setPlan($plan)
    {
        return $this->plan = $plan;
    }

}
