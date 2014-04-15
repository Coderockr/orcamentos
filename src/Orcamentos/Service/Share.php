<?php

namespace Orcamentos\Service;

use Orcamentos\Model\Share as ShareModel;
use Intervention\Image\Image;
use Exception;
  
/**
 * Share Entity
 *
 * @category Orcamentos
 * @package Service
 * @author  Mateus Guerra<mateus@coderockr.com>
 */
class Share
{
    /**
     * Function that saves a new Share
     *
     * @return                Function used to save a new Share
     */
    public static function save($data, $em)
    {
        $data = json_decode($data);

        if (!isset($data->email) || !isset($data->quoteId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $quote = $em->getRepository("Orcamentos\Model\Quote")->find($data->quoteId);

        foreach ($data->email as $email) {
            $share = new ShareModel();
            $share->setQuote($quote);
            $share->setEmail($email);
            $share->setSent(false);
            
            $em->persist($share);
        }

        $em->flush();

        return $data->email;
    }
}
