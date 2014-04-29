<?php

namespace Orcamentos\Service;

use Orcamentos\Model\Share as ShareModel;
use Orcamentos\Model\ShareNote as ShareNoteModel;
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
           return false;
       }

        $quote = $em->getRepository("Orcamentos\Model\Quote")->find($data->quoteId);
        
        $shares = array();

        foreach ($data->email as $email) {
            $share = $em->getRepository('Orcamentos\Model\Share')->findOneBy(array('quote'=> $quote, 'email' => $email));
            if( !$share) {
                $share = new ShareModel();
                $share->setQuote($quote);
                $share->setEmail($email);
                $share->setSent(false);
                
                $em->persist($share);
                $shares[] = $share;
            } 
        }

        $em->flush();

        $result = array();
        foreach ($shares as $key => $share) {
            $result[$key]['id'] = $share->getId();
            $result[$key]['email'] = $share->getEmail();
        }

        return $result;
    }


    /**
     * Function that saves a new ShareNote
     *
     * @return                Function used to save a new ShareNote
     */
    public static function comment($data, $em)
    {
        $data = json_decode($data);

        if (!isset($data->note) || !isset($data->shareId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $share = $em->getRepository("Orcamentos\Model\Share")->find($data->shareId);

        $note = new ShareNoteModel();
        $note->setShare($share);
        $note->setNote($data->note);
        
        $em->persist($note);

        $em->flush();

        return $note;
    }

    /**
     * Function that sets the share to be resend
     *
     * @return                Function used to set sent to false in a share
     */
    public static function resend($data, $em)
    {
        $data = json_decode($data);

        if (!isset($data->shareId) ) {
            throw new Exception("Invalid Parameters", 1);
        }

        $share = $em->getRepository("Orcamentos\Model\Share")->find($data->shareId);
        $share->setSent(0);
        $em->persist($share);
        $em->flush();

        return $share->getEmail();
    }
}
