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
     * @param                 array $data
     * @return                Orcamentos\Model\Quote $quote
     */
    public function save($data)
    {
        $data = json_decode($data);

        if (!isset($data->projectId) || !isset($data->version) || !isset($data->taxes) || !isset($data->privateNotes)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $project = $this->em->getRepository("Orcamentos\Model\Project")->find($data->projectId);
        
        $quote->getQuote($data);

        if(!$quote->getProject()){
            $quote->setProject($project);
        }

        if(!$quote->getVersion()){
            $quote->setVersion($data->version);
        }

        $quote->setStatus($data->status);
        $quote->setPrivateNotes($data->privateNotes);
        $quote->setProfit($data->profit);
        $quote->setCommission($data->commission);
        $quote->setDueDate($data->dueDate);
        $quote->setTaxes($data->taxes);
        $quote->setDeadline($data->deadline);
        $quote->setPriceDescription($data->priceDescription);
        $quote->setPaymentType($data->paymentType);
       
        $this->em->persist($quote);

        $this->addResourceQuotes($data->quoteResource, $quote);

        try {
            $this->em->flush();
            return $quote;
        } catch (Exception $e) {
          echo $e->getMessage();
        }
    }  

    /**
     * Function used to get a already saved or a new Quote Object
     * @param                 array $data
     * @return                Orcamentos\Model\Quote $quote
     */
    private function getQuote($data){

        $quote = null;

        if ( isset($data->id) ) {
            $quote = $this->em->getRepository("Orcamentos\Model\Quote")->find($data->id);
        }

        if (!$quote) {
            $quote = new QuoteModel();
        }

        return $quote;
    }

    /**
     * Function used to add amounts of resources (Orcamentos\Model\ResourceQuote) to the quote
     * @param                 array $resourceQuotes
     * @param                 Orcamentos\Model\Quote $quote
     * @return                void
     */
    public function addResourceQuotes($resourceQuotes, $quote){

        $quoteResourceCollection = $quote->getResourceQuoteCollection();

        if (!$quoteResourceCollection){
            $quoteResourceCollection = new ArrayCollection();
        }

        $quoteResourceCollection->clear();

        if(isset($resourceQuotes)){
            foreach ($resourceQuotes as $id => $amount) {

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
    }

    /**
     * Function that duplicates a Quote
     * @param                 array $data
     * @return                Orcamentos\Model\Quote $quote
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

        $resourceQuoteCollection = $quote->getResourceQuoteCollection();

        $resources = array();

        foreach ($resourceQuoteCollection as $resourceQuote){
            $resource = $resourceQuote->getResource();
            $resources[$resource->getId()] = $resourceQuote->getAmount();
        }
        
        $this->addResourceQuotes($resources, $duplicate);

        try {
            $this->em->flush();
            return $duplicate;
        } catch (Exception $e) {
          echo $e->getMessage();
        }
    }
}
