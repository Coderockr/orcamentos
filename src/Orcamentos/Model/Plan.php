<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Plan")
 */
class Plan
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
     * @ORM\Column(type="float", nullable=false )
     *
     * @var float
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=false )
     *
     * @var integer
     */
    private $quoteLimit;

    /**
     * @ORM\OneToMany(targetEntity="Company", mappedBy="plan", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * 
     * @var Doctrine\Common\Collections\Collection
     */
    protected $companyCollection;

    public function __construct()
    {
        $this->setCreated(date('Y-m-d H:i:s'));
    }

    public function getCompanyCollection()
    {
        return $this->companyCollection;
    }
    
    public function setCompanyCollection($companyCollection)
    {
        return $this->companyCollection = $companyCollection;
    }

    public function getQuoteLimit()
    {
        return $this->quoteLimit;
    }
    
    public function setQuoteLimit($quoteLimit)
    {
        return $this->quoteLimit = $quoteLimit;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        return $this->name = $name;
    }

    public function getPrice()
    {
        return $this->price;
    }
    
    public function setPrice($price)
    {
        return $this->price = $price;
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
