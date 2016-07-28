<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 10/11/15
 * Time: 15:39
 */
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Requisite")
 */
class Requisite extends Entity
{
    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var text
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=false)
     *
     * @var float
     */
    private $expectedAmount;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    private $spentAmount;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="requisitesCollection", cascade={"persist", "merge", "refresh"})
     *
     * @var Project
     */
    protected $project;

    /**
     * @ORM\OneToMany(targetEntity="RequisiteQuote", mappedBy="requisite", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     *
     * @var Doctrine\Common\Collections\Collection
     */
    protected $requisiteQuoteCollection;

    public function __construct()
    {
        $this->setCreated(date('Y-m-d H:i:s'));
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        return $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        return $this->description = $description;
    }

    /**
     * @return float
     */
    public function getExpectedAmount()
    {
        return $this->expectedAmount;
    }

    /**
     * @param float $expectedAmount
     */
    public function setExpectedAmount($expectedAmount)
    {
        $this->expectedAmount = $expectedAmount;
    }

    /**
     * @return float
     */
    public function getSpentAmount()
    {
        return $this->spentAmount;
    }

    /**
     * @param float $spentAmount
     */
    public function setSpentAmount($spentAmount)
    {
        $this->spentAmount = $spentAmount;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function setProject($project)
    {
        return $this->project = $project;
    }

    /**
     * @param Doctrine\Common\Collections\Collection $requisiteQuoteCollection
     */
    public function setRequisiteQuoteCollection($requisiteQuoteCollection)
    {
        $this->requisiteQuoteCollection = $requisiteQuoteCollection;
    }

    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getRequisiteQuoteCollection()
    {
        return $this->requisiteQuoteCollection;
    }

}