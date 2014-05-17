<?php

namespace Orcamentos\Service;

use Orcamentos\Model\Share as ShareModel;
use Orcamentos\Model\ShareNote as ShareNoteModel;
use Intervention\Image\Image;
use Swift_Message;
use Exception;
  
/**
 * Share Entity
 *
 * @category Orcamentos
 * @package Service
 * @author  Mateus Guerra<mateus@coderockr.com>
 */
class Share extends Service
{
    /**
     * Function that saves a new Share
     *
     * @return                Function used to save a new Share
     */
    public function save($data)
    {
        $data = json_decode($data);
        if (!isset($data->email) || !isset($data->quoteId)) {
           return false;
       }

        $quote = $this->em->getRepository("Orcamentos\Model\Quote")->find($data->quoteId);
        
        $shares = array();

        foreach ($data->email as $this->email) {
            $share = $this->em->getRepository('Orcamentos\Model\Share')->findOneBy(array('quote'=> $quote, 'email' => $this->email));
            if( !$share) {
                $share = new ShareModel();
                $share->setQuote($quote);
                $share->setEmail($this->email);
                $share->setSent(true);
                $hash = hash('sha256', $this->email . $share->getQuote()->getProject()->getName() . $share->getQuote()->getVersion());
                $share->setHash($hash);

                // Prod
                $url = 'http://orcamentos.coderockr.com/share/' . $hash;
                $token = 'ed0e929d7ff5b92c480f34e4851a96945dd4702b';

                // Desenvolvimento
                // $url = 'http://orcamentos.dev:8080/share/' . $hash;
                // $token = 'eb9b61dd4df8daa4d8e679a4bb8e187034dfcd7a';
               
                $bitlyJson = fopen("https://api-ssl.bitly.com/v3/shorten?access_token=" . $token . "&longUrl=" . $url, 'rb');
                $bitly =  json_decode(stream_get_contents($bitlyJson), true);
                $share->setShortUrl($bitly['data']['url']);
                $this->em->persist($share);
                $shares[] = $share;
            } 
        }

        $this->em->flush();

        $result = array();
        foreach ($shares as $key => $share) {
            $result[$key]['id'] = $share->getId();
            $result[$key]['email'] = $share->getEmail();
            $result[$key]['shortUrl'] = $share->getShortUrl();
        }

        return $result;
    }


    /**
     * Function that saves a new ShareNote
     *
     * @return                Function used to save a new ShareNote
     */
    public function comment($data)
    {
        $data = json_decode($data);

        if (!isset($data->note) || !isset($data->shareId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $share = $this->em->getRepository("Orcamentos\Model\Share")->find($data->shareId);

        $note = new ShareNoteModel();
        $note->setShare($share);
        $note->setNote($data->note);
        
        $this->em->persist($note);

        $this->em->flush();

        return $note;
    }

    /**
     * Function that sets the share to be resend
     *
     * @return                Function used to set sent to false in a share
     */
    public function resend($data)
    {
        $data = json_decode($data);

        if (!isset($data->shareId) ) {
            throw new Exception("Invalid Parameters", 1);
        }

        $share = $this->em->getRepository("Orcamentos\Model\Share")->find($data->shareId);
        $share->setSent(0);
        $this->em->persist($share);
        $this->em->flush();

        return $share->getEmail();
    }


    /**
     * Function that sends the emails that weren't sent
     *
     * @return                
     */
    public function sendEmails($limit, $app)
    {
        if (!isset($limit) ) {
            throw new Exception("Invalid Parameters", 1);
        }

        $this->em = $app['orm.em'];

        $shares = $this->em->getRepository("Orcamentos\Model\Share")->findBy( array( 'sent' => 0 ), array( 'id' => 'ASC' ), $limit );
        
        foreach ($shares as $i => $share) {
            $quote = $share->getQuote();
            $quoteVersion = $quote->getVersion();
            $project = $quote->getProject();
            $projectName = $project->getName();
            $companyName = $project->getCompany()->getName();

            $subject = " A empresa " . $companyName . " compartilhou o orçamento " . $quoteVersion . " do projeto " . $projectName;
            $link = 'orcamentos.coderockr.com';
            $body = 'Veja o orçamento no link http://' . $link . '/share/' . $share->getHash();
            
            $message = Swift_Message::newInstance()
                ->setFrom(array('contato@coderockr.com'))
                ->setTo(array($share->getEmail()))
                ->setReplyTo('contato@coderockr.com')
                ->setSubject($subject)
                ->setBody($body);

            $app['mailer']->send($message);

            $share->setSent(1);

            $this->em->persist($share);

        }

        $this->em->flush();
        
        return true;
    }


    /**
     * Function that deletes a Private message 
     *
     * @return                Function used to delete a Private message
     */
    public function removeComment($data)
    {
        $data = json_decode($data);

        if ( !isset($data->noteId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $note = $this->em->getRepository("Orcamentos\Model\ShareNote")->find($data->noteId);

        $this->em->remove($note);

        $this->em->flush();

        return $note;
    }
}
