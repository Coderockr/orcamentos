<?php

namespace Orcamentos\Service;

use Orcamentos\Model\Resource as ResourceModel;
  
/**
 * Resource Entity
 *
 * @category Orcamentos
 * @package Service
 * @author  Mateus Guerra<mateus@coderockr.com>
 */
class Resource
{
    /**
     * Function that saves a new Resource
     *
     * @return                Function used to save a new Resource
     */
    public static function save($data, $logotype, $em)
    {
        $data = json_decode($data);

        if (!isset($data->name) || !isset($data->responsable) || !isset($data->email) || !isset($data->companyId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $resource = null;
        if ( isset($data->id) ) {
            $resource = $em->getRepository("Orcamentos\Model\Resource")->find($data->id);
        }

        if (!$resource) {
            $resource = new ResourceModel();
        }

        $resource->setName($data->name);
        $resource->setResponsable($data->responsable);
        $resource->setEmail($data->email);

        if (isset($data->cnpj)) {
            $resource->setCnpj($data->cnpj);
        }
        if (isset($data->telephone)) {
            $resource->setTelephone($data->telephone);
        }
        $company = $em->getRepository('Orcamentos\Model\Company')->find($data->companyId);
        
        if (isset($company)) {
            $resource->setCompany($company);
        }
        
        if (isset($logotype)) {
            $originalName = $logotype->getResourceOriginalName();
            $components = explode('.', $originalName);
            $fileName = md5(time()) . '.' . end($components);
            
            $file = Image::make($logotype->getPathName())->resize(null, 80, true, false);
            $file->save("public/img/logotypes/" . $fileName );
            $resource->setLogotype($fileName);
        }

        $em->persist($resource);
        $em->flush();

        return $resource;
    }
}
