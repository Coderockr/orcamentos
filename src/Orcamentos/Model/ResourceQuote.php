<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ResourceQuote")
 */
class ResourceQuote
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
     * @ORM\ManyToOne(targetEntity="Resource", cascade={"persist", "merge", "refresh"})
     * 
     * @var Resource
     */
    protected $resource; 

    /**
     * @ORM\ManyToOne(targetEntity="Quote", cascade={"persist", "merge", "refresh"})
     * 
     * @var Quote
     */
    protected $quote;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    private $amount;

    public function __construct()
    {
        $this->setCreated(date('Y-m-d H:i:s'));
    }

    public function getResource()
    {
        return $this->resource;
    }
    
    public function setResource($resource)
    {
        return $this->resource = $resource;
    }
    
    public function getQuote()
    {
        return $this->quote;
    }
    
    public function setQuote($quote)
    {
        return $this->quote = $quote;
    }

    public function getAmount()
    {
        return $this->amount;
    }
    
    public function setAmount($amount)
    {
        return $this->amount = $amount;
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
