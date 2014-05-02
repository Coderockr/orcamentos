<?php

namespace Orcamentos\Service;

use Orcamentos\Model\Resource as ResourceModel;
use Orcamentos\Model\Quote as QuoteModel;
use Orcamentos\Model\ResourceQuote as ResourceQuoteModel;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

/**
 * Quote Entity
 *
 * @category Orcamentos
 * @package Service
 * @author  Mateus Guerra<mateus@coderockr.com>
 */
class Quote
{
    /**
     * Function that saves a new Quote
     *
     * @return                Function used to save a new Quote
     */
    public static function save($data, $em)
    {
        $data = json_decode($data);

        if (!isset($data->projectId) || !isset($data->version)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $project = $em->getRepository("Orcamentos\Model\Project")->find($data->projectId);
        
        $quote = null;
        if ( isset($data->id) ) {
            $quote = $em->getRepository("Orcamentos\Model\Quote")->find($data->id);
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
        
        $quote->setDeadline($data->deadline);
        $quote->setPriceDescription($data->priceDescription);
        $quote->setPaymentType($data->paymentType);

        $em->persist($quote);

        $quoteResourceCollection = $quote->getResourceQuoteCollection();

        if (!$quoteResourceCollection){
            $quoteResourceCollection = new ArrayCollection();
        }

        $quoteResourceCollection->clear();

        foreach ($data->quoteResource as $id => $amount) {

            $resource = $em->getRepository("Orcamentos\Model\Resource")->find($id);

            $quoteResource = new ResourceQuoteModel();
            $quoteResource->setResource($resource);
            $quoteResource->setQuote($quote);
            $quoteResource->setAmount($amount);
            $quoteResource->setValue($resource->getCost());

            $em->persist($quoteResource);

            $quoteResourceCollection->add($quoteResource);
        }

        $em->flush();

        return $quote;
    }
}
