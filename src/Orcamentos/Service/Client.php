<?php

namespace Orcamentos\Service;

use Orcamentos\Model\Client as ClientModel;
use Intervention\Image\Image;
  
/**
 * Client Entity
 *
 * @category Orcamentos
 * @package Service
 * @author  Mateus Guerra<mateus@coderockr.com>
 */
class Client
{
    /**
     * Function that saves a new client
     *
     * @return                Function used to save a new Client
     */
    public static function save($data, $logotype, $em)
    {
        $data = json_decode($data);

        if (!isset($data->name) || !isset($data->responsable) || !isset($data->email) || !isset($data->companyId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $client = null;
        if ( isset($data->id) ) {
            $client = $em->getRepository("Orcamentos\Model\Client")->find($data->id);
        }

        if (!$client) {
            $client = new ClientModel();
        }

        $client->setName($data->name);
        $client->setResponsable($data->responsable);
        $client->setEmail($data->email);

        if (isset($data->cnpj)) {
            $client->setCnpj($data->cnpj);
        }
        if (isset($data->telephone)) {
            $client->setTelephone($data->telephone);
        }
        $company = $em->getRepository('Orcamentos\Model\Company')->find($data->companyId);
        
        if (isset($company)) {
            $client->setCompany($company);
        }
        
        if (isset($logotype)) {
            $originalName = $logotype->getClientOriginalName();
            $components = explode('.', $originalName);
            $fileName = md5(time()) . '.' . end($components);
            
            $file = Image::make($logotype->getPathName())->grab(80);

            $file->save("public/img/logotypes/" . $fileName );
            $client->setLogotype($fileName);
        }

        $em->persist($client);
        $em->flush();

        return $client;
    }
}
