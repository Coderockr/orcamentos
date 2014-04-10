<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="ClientNote")
 */
class ClientNote
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
     * @ORM\ManyToOne(targetEntity="Client", cascade={"persist", "merge", "refresh"})
     * 
     * @var Client
     */
    protected $client; 

    /**
     * @ORM\ManyToOne(targetEntity="Quote", inversedBy="clientNotesCollection", cascade={"persist", "merge", "refresh"})
     * 
     * @var Quote
     */
    protected $quote;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var text
     */
    private $note;

    public function __construct()
    {
        $this->setCreated(date("Y-m-d H:i:s"));
    }
   
    public function getClient()
    {
        return $this->client;
    }
    
    public function setClient($client)
    {
        return $this->client = $client;
    }   

    public function getQuote()
    {
        return $this->quote;
    }
    
    public function setQuote($quote)
    {
        return $this->quote = $quote;
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
