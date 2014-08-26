<?php
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass
  * @ORM\Entity
  * @ORM\InheritanceType("SINGLE_TABLE")
  * @ORM\DiscriminatorColumn(name="type", type="string")
  * @ORM\DiscriminatorMap({
  *     "equipment" = "EquipmentType",
  *     "service" = "ServiceType",
  *     "human" = "HumanType",
  * })
  */
abstract class Type extends Entity
{
    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Resource", mappedBy="type", cascade={"all"}, orphanRemoval=false, fetch="LAZY")
     *
     * @var Doctrine\Common\Collections\Collection
     */
    protected $resourceCollection;

    public function __construct()
    {
        $this->setCreated(date("Y-m-d H:i:s"));
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        return $this->name = filter_var($name, FILTER_SANITIZE_STRING);
    }
}
