<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Share")
 */
class Share
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
     * @ORM\ManyToOne(targetEntity="Quote", inversedBy="shareCollection", cascade={"persist", "merge", "refresh"})
     * 
     * @var Quote
     */
    protected $quote;

    /**
     * @ORM\Column(type="string", length=150)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    private $sent;

    /**
     * @ORM\OneToMany(targetEntity="View", mappedBy="share", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * 
     * @var Doctrine\Common\Collections\Collection
     */
    protected $viewCollection;

    public function __construct()
    {
        $this->setCreated(date('Y-m-d H:i:s'));
    }

    public function getQuote()
    {
        return $this->quote;
    }
    
    public function setQuote($quote)
    {
        return $this->quote = $quote;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        return $this->email = $email;
    }    

    public function getSent()
    {
        return $this->sent;
    }
    
    public function setSent($sent)
    {
        return $this->sent = $sent;
    }
        
    public function getViewCollection()
    {
        return $this->viewCollection;
    }
    
    public function setViewCollection($viewCollection)
    {
        return $this->viewCollection = $viewCollection;
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
