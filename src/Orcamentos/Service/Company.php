<?php

namespace Orcamentos\Service;

use Orcamentos\Model\Company as CompanyModel;
use Intervention\Image\Image;
use Exception;
  
/**
 * Company Entity
 *
 * @category Orcamentos
 * @package Service
 * @author  Mateus Guerra<mateus@coderockr.com>
 */
class Company
{
    /**
     * Function that saves a new company
     *
     * @return                Function used to save a new company
     */
    public static function save($data, $logotype, $em)
    {
        $data = json_decode($data);

        if (!isset($data->name) || !isset($data->responsable) || !isset($data->telephone) || !isset($data->companyId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $company = null;
        if ( isset($data->companyId) ) {
            $company = $em->getRepository("Orcamentos\Model\Company")->find($data->companyId);
        }
        
        if (!$company) {
            throw new Exception("Error Processing Request", 1);
        }

        $company->setName($data->name);
        $company->setResponsable($data->responsable);

        if (isset($data->site)) {
            $company->setSite($data->site);
        }
        
        if (isset($data->telephone)) {
            $company->setTelephone($data->telephone);
        }

        if (isset($logotype)) {
            $originalName = $logotype->getClientOriginalName();
            $components = explode('.', $originalName);
            $fileName = md5(time()) . '.' . end($components);
            
            $file = Image::make($logotype->getPathName())->resize(null, 80, true, false);
            $file->save("public/img/logotypes/" . $fileName );
            $company->setLogotype($fileName);
        }

        $em->persist($company);
        $em->flush();

        return $company;
    }
}
