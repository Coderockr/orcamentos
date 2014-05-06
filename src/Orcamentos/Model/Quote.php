<?php

namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Quote")
 */
class Quote
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
     * @ORM\Column(type="datetime",nullable=true)
     * @var datetime
     */
    protected $dueDate;

    /**
     * @ORM\Column(type="float", nullable=false )
     *
     * @var float
     */
    private $taxes;

    /**
     * @ORM\Column(type="string", length=150)
     *
     * @var string
     */
    private $version;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $status;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    private $profit;

    /**
     * @ORM\Column(type="text",nullable=true)
     *
     * @var text
     */
    private $privateNotes;

    /**
     * @ORM\Column(type="text",nullable=true)
     *
     * @var text
     */
    private $deadline;

    /**
     * @ORM\Column(type="text",nullable=true)
     *
     * @var text
     */
    private $priceDescription;

    /**
     * @ORM\Column(type="text",nullable=true)
     *
     * @var text
     */
    private $paymentType;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="quoteCollection", cascade={"persist", "merge", "refresh"})
     * 
     * @var Project
     */
    protected $project;

    /**
     * @ORM\OneToMany(targetEntity="ResourceQuote", mappedBy="quote", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * 
     * @var Doctrine\Common\Collections\Collection
     */
    protected $resourceQuoteCollection;

    /**
     * @ORM\OneToMany(targetEntity="Share", mappedBy="quote", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * 
     * @var Doctrine\Common\Collections\Collection
     */
    protected $shareCollection;
    
    public function __construct()
    {
        $this->setCreated(date('Y-m-d H:i:s'));
    }

    public function getVersion()
    {
        return $this->version;
    }
    
    public function setVersion($version)
    {
        return $this->version = $version;
    }
    
    public function getPrivateNotes()
    {
        return $this->privateNotes;
    }
    
    public function setPrivateNotes($privateNotes)
    {
        return $this->privateNotes = $privateNotes;
    } 

    public function getStatus()
    {
        return $this->status;
    }
    
    public function setStatus($status)
    {
        return $this->status = $status;
    }    

    public function getTaxes()
    {
        return $this->taxes;
    }
    
    public function setTaxes($taxes)
    {
        return $this->taxes = $taxes;
    }
    public function getProject()
    {
        return $this->project;
    }
    
    public function setProject($project)
    {
        return $this->project = $project;
    }    

    public function getProfit()
    {
        return $this->profit;
    }
    
    public function setProfit($profit)
    {
        return $this->profit = $profit;
    }   

    public function getDeadline()
    {
        return $this->deadline;
    }
    
    public function setDeadline($deadline)
    {
        return $this->deadline = $deadline;
    }   

    public function getPriceDescription()
    {
        return $this->priceDescription;
    }
    
    public function setPriceDescription($priceDescription)
    {
        return $this->priceDescription = $priceDescription;
    }    

    public function getPaymentType()
    {
        return $this->paymentType;
    }
    
    public function setPaymentType($paymentType)
    {
        return $this->paymentType = $paymentType;
    }    

    public function getResourceQuoteCollection()
    {
        return $this->resourceQuoteCollection;
    }
    
    public function setResourceQuoteCollection($resourceQuoteCollection)
    {
        return $this->resourceQuoteCollection = $resourceQuoteCollection;
    }

    public function getShareCollection()
    {
        return $this->shareCollection;
    }
    
    public function setShareCollection($shareCollection)
    {
        return $this->shareCollection = $shareCollection;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function getDueDate()
    {
        if ( !$this->dueDate ){
            return null;
        }

        return $this->dueDate->format('Y-m-d H:i:s');
    }
    
    public function setDueDate($dueDate)
    {
        $this->dueDate = null;
        
        if ($dueDate != null){
            $this->dueDate = \DateTime::createFromFormat('d/m/Y', $dueDate);    
        }
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
