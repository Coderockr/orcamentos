<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User extends Entity
{
    /**
     * @ORM\Column(type="string", length=150)
     *
     * @var string
     */
    private $name;

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
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="userCollection", cascade={"persist", "merge", "refresh"})
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
}
