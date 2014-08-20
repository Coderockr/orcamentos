<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="share")
 */
class Share extends Entity
{
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
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $hash;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $shortUrl;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    private $sent;

    /**
     * @ORM\OneToMany(targetEntity="View", mappedBy="share", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * 
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $viewCollection;

    /**
     * @ORM\OneToMany(targetEntity="ShareNote", mappedBy="share", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * @ORM\OrderBy({"created" = "DESC"})
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $shareNotesCollection;

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
    
    public function getHash()
    {
        return $this->hash;
    }
    
    public function setHash($hash)
    {
        return $this->hash = $hash;
    }  

    public function getShortUrl()
    {
        return $this->shortUrl;
    }
    
    public function setShortUrl($shortUrl)
    {
        return $this->shortUrl = $shortUrl;
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

    public function getShareNotesCollection()
    {
        return $this->shareNotesCollection;
    }
    
    public function setShareNotesCollection($shareNotesCollection)
    {
        return $this->shareNotesCollection = $shareNotesCollection;
    }
}
