<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="view")
 */
class View extends Entity
{
    /**
     * @ORM\ManyToOne(targetEntity="Share", inversedBy="viewCollection", cascade={"persist", "merge", "refresh"})
     * 
     * @var Share
     */
    protected $share;

    public function __construct()
    {
        $this->setCreated(date('Y-m-d H:i:s'));
    }

    public function getShare()
    {
        return $this->share;
    }
    
    public function setShare($share)
    {
        return $this->share = $share;
    }
}
