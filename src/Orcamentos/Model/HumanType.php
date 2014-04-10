<?php 
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
  * @ORM\Entity
  */
class HumanType extends Type
{
    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $contractType;

    public function getContractType()
    {
        return $this->contractType;
    }
    
    public function setContractType($contractType)
    {
        return $this->contractType = $contractType;
    }

}
