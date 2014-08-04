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
     * @param                 array $data
     * @return                array $result
     */
    public function save($data)
    {
        $data = json_decode($data);

        if (!isset($data->email) || !isset($data->quoteId)) {
           return false;
       }

        $quote = $this->em->getRepository("Orcamentos\Model\Quote")->find($data->quoteId);
        
        $shares = array();

        //@todo: melhorar isso, buscar a configuração do $app
        $config = require_once __DIR__ . '/../../../config/config.php';

        if (!$config) {
            throw new \Exception("Error Processing Config", 1);
        }

        foreach ($data->email as $this->email) {
            $share = $this->em->getRepository('Orcamentos\Model\Share')->findOneBy(array('quote'=> $quote, 'email' => $this->email));
            if( !$share) {
                $share = new ShareModel();
                $share->setQuote($quote);
                $share->setEmail($this->email);
                $share->setSent(true);
                $hash = hash('sha256', $this->email . $share->getQuote()->getProject()->getName() . $share->getQuote()->getVersion());
                $share->setHash($hash);

                $url = $config['bitly']['url'] . $hash;
               
                $bitlyJson = fopen("https://api-ssl.bitly.com/v3/shorten?access_token=" . $config['bitly']['token'] . "&longUrl=" . $url, 'rb');
                $bitly =  json_decode(stream_get_contents($bitlyJson), true);
                $share->setShortUrl($bitly['data']['url']);
                $this->em->persist($share);
                $shares[] = $share;
            } 
        }
        
        try {
            $this->em->flush();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

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
            throw new Exception("Parâmetros inválidos", 1);
        }

        $share = $this->em->getRepository("Orcamentos\Model\Share")->find($data->shareId);

        $note = new ShareNoteModel();
        $note->setShare($share);
        $note->setNote($data->note);
        
        try {
               
            $this->em->persist($note);

            $this->em->flush();

            return $note;

        } catch (Exception $e) {
            echo $e->getMessage();
        }
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
            throw new Exception("Parâmetros inválidos", 1);
        }

        $share = $this->em->getRepository("Orcamentos\Model\Share")->find($data->shareId);
        $share->setSent(0);

        try {
               
            $this->em->persist($share);
            $this->em->flush();
            return $share->getEmail();
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
     * Function that sends the emails that weren't sent
     *
     * @return                
     */
    public function sendEmails($limit, $app)
    {
        if (!isset($limit) ) {
            throw new Exception("Parâmetros inválidos", 1);
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

        try {
            $this->em->flush();
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
     * Function that deletes a Private message 
     *
     * @return                Orcamentos\Model\ShareNote $note
     */
    public function removeComment($data)
    {
        $data = json_decode($data);

        if ( !isset($data->noteId)) {
            throw new Exception("Parâmetros inválidos", 1);
        }

        $note = $this->em->getRepository("Orcamentos\Model\ShareNote")->find($data->noteId);

        try {

            $this->em->remove($note);

            $this->em->flush();

            return $note;
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
