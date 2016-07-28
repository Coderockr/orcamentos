<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 27/07/16
 * Time: 16:39
 */
namespace Orcamentos\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="RequisiteQuote")
 */
class RequisiteQuote extends Entity
{
    /**
     * @ORM\ManyToOne(targetEntity="Requisite", inversedBy="requisiteQuoteCollection",cascade={"persist", "merge", "refresh"})
     *
     * @var Requisite
     */
    protected $requisite;

    /**
     * @ORM\ManyToOne(targetEntity="Quote", inversedBy="requisiteQuoteCollection", cascade={"persist", "merge", "refresh"})
     *
     * @var Quote
     */
    protected $quote;

    public function __construct()
    {
        $this->setCreated(date('Y-m-d H:i:s'));
    }

    /**
     * @return Quote
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * @return mixed
     */
    public function getRequisite()
    {
        return $this->requisite;
    }

    /**
     * @param Quote $quote
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;
    }

    /**
     * @param mixed $requisite
     */
    public function setRequisite($requisite)
    {
        $this->requisite = $requisite;
    }

}