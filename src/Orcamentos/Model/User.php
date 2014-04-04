<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User
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
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     *
     * @var string
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @var string
     */
    private $password;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @var string
     */
    private $admin;

    /**
     * @ORM\ManyToOne(targetEntity="Company", cascade={"persist", "merge", "refresh"})
     * 
     * @var Company
     */
    protected $company;

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
    
    public function getLogin()
    {
        return $this->login;
    }
    
    public function setLogin($login)
    {
        return $this->login = $login;
    }

    public function getCompany()
    {
        return $this->company;
    }
    
    public function setCompany($company)
    {
        return $this->company = $company;
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
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {

        return $this->password = $password;
    }

    public function getAdmin()
    {
        return $this->admin;
    }
    
    public function setAdmin($admin)
    {
        return $this->admin = $admin;
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
