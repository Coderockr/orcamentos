<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="resourcequote")
 */
class ResourceQuote extends Entity
{
    /**
     * @ORM\ManyToOne(targetEntity="Resource", inversedBy="resourceQuoteCollection",cascade={"persist", "merge", "refresh"})
     * 
     * @var Resource
     */
    protected $resource; 

    /**
     * @ORM\ManyToOne(targetEntity="Quote", inversedBy="resourceQuoteCollection", cascade={"persist", "merge", "refresh"})
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

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    private $value;

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

    public function getValue()
    {
        return $this->value;
    }
    
    public function setValue($value)
    {
        return $this->value = $value;
    }
}
