<?php

namespace Orcamentos\Service;

use Orcamentos\Model\Resource as ResourceModel;
use Orcamentos\Model\Quote as QuoteModel;
use Orcamentos\Model\ResourceQuote as ResourceQuoteModel;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Datetime;

/**
 * Quote Entity
 *
 * @category Orcamentos
 * @package Service
 * @author  Mateus Guerra<mateus@coderockr.com>
 */
class Quote extends Service
{
    /**
     * Function that saves a new Quote
     *
     * @return                Function used to save a new Quote
     */
    public function save($data)
    {
        $data = json_decode($data);

        if (!isset($data->projectId) || !isset($data->version) || !isset($data->taxes) || !isset($data->privateNotes)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $project = $this->em->getRepository("Orcamentos\Model\Project")->find($data->projectId);
        
        $quote = null;
        if ( isset($data->id) ) {
            $quote = $this->em->getRepository("Orcamentos\Model\Quote")->find($data->id);
        }

        if (!$quote) {
            $quote = new QuoteModel();
            $quote->setProject($project);
            $quote->setVersion($data->version);
        }

        $quote->setStatus($data->status);

        $quote->setPrivateNotes($data->privateNotes);
        
        if(isset($data->profit)){
            $quote->setProfit($data->profit);
        }  

        if(isset($data->commission)){
            $quote->setCommission($data->commission);
        }

        if(isset($data->dueDate)){
            $quote->setDueDate($data->dueDate);
        }
        
        if(isset($data->taxes)){
            $quote->setTaxes($data->taxes);
        }

        $quote->setDeadline($data->deadline);
        $quote->setPriceDescription($data->priceDescription);
        $quote->setPaymentType($data->paymentType);

        $this->em->persist($quote);

        $quoteResourceCollection = $quote->getResourceQuoteCollection();

        if (!$quoteResourceCollection){
            $quoteResourceCollection = new ArrayCollection();
        }

        $quoteResourceCollection->clear();

        if(isset($data->quoteResource)){
            foreach ($data->quoteResource as $id => $amount) {

                $resource = $this->em->getRepository("Orcamentos\Model\Resource")->find($id);

                $quoteResource = new ResourceQuoteModel();
                $quoteResource->setResource($resource);
                $quoteResource->setQuote($quote);
                $quoteResource->setAmount($amount);
                $quoteResource->setValue($resource->getCost());

                $this->em->persist($quoteResource);

                $quoteResourceCollection->add($quoteResource);
            }
        }

        $this->em->flush();

        return $quote;
    }  

    /**
     * Function that duplicates a Quote
     *
     * @return                A duplicate Quote
     */
    public function duplicate($data)
    {
        $data = json_decode($data);

        if (!isset($data->quoteId) ) {
            throw new Exception("Invalid Parameters", 1);
        }

        $quote = $this->em->getRepository("Orcamentos\Model\Quote")->find($data->quoteId);
        
        if (!$quote) {
            throw new Exception("Orçamento não existe", 1);
        }

        $duplicate = new QuoteModel();
        $duplicate->setProject($quote->getProject());
        $duplicate->setVersion(count($quote->getProject()->getQuoteCollection()) + 1);
        $duplicate->setStatus($quote->getStatus());
        $duplicate->setPrivateNotes($quote->getPrivateNotes());
        $duplicate->setProfit($quote->getProfit());
        $duplicate->setCommission($quote->getCommission());
        $duplicate->setTaxes($quote->getTaxes());
        $duplicate->setDueDate($quote->getDueDate());
        $duplicate->setDeadline($quote->getDeadline());
        $duplicate->setPriceDescription($quote->getPriceDescription());
        $duplicate->setPaymentType($quote->getPaymentType());

        $this->em->persist($duplicate);

        $quoteResourceQuoteCollection = $quote->getResourceQuoteCollection();
        $duplicateResourceQuoteCollection = $quote->getResourceQuoteCollection();

        if(isset($quoteResourceQuoteCollection)){
            foreach ($quoteResourceQuoteCollection as $resourceQuote) {

                $resource = $resourceQuote->getResource();

                $quoteResource = new ResourceQuoteModel();
                $quoteResource->setResource($resource);
                $quoteResource->setQuote($duplicate);
                $quoteResource->setAmount($resourceQuote->getAmount());
                $quoteResource->setValue($resource->getCost());

                $this->em->persist($quoteResource);

                $duplicateResourceQuoteCollection->add($quoteResource);
            }
        }

        $this->em->flush();

        return $duplicate;
    }
}
