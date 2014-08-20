<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="plan")
 */

class Plan extends Entity
{
    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="float", nullable=true )
     *
     * @var float
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true )
     *
     * @var integer
     */
    private $quoteLimit;

     /* @ORM\Column(type="text", nullable=false)
     *
     * @var string
     */
    private $description;
    
    /**
     * @ORM\OneToMany(targetEntity="Company", mappedBy="plan", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * 
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $companyCollection;

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
        return $this->name = filter_var($name, FILTER_SANITIZE_STRING);
    }

    public function getPrice()
    {
        return $this->price;
    }
    
    public function setPrice($price)
    {
        return $this->price = $price;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

}
