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

        if (!isset($data->name) || !isset($data->responsable) || !isset($data->telephone)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $company = null;

        if (isset($data->companyId) ) {
            $company = $em->getRepository("Orcamentos\Model\Company")->find($data->companyId);
        }

        if (!$company) {
            $company = $em->getRepository("Orcamentos\Model\Company")->findOneBy(array('name' => $data->name));
            return $company;
        }

        if (!$company) {
            $company = new CompanyModel();
        }

        $taxes = 6;

        if(isset($data->taxes)){
            $taxes = $data->taxes;
        }

        $company->setName($data->name);
        $company->setResponsable($data->responsable);
        $company->setTaxes($taxes);

        if (isset($data->city)) {
            $company->setCity($data->city);
        }

        if (isset($data->site)) {
            $company->setSite($data->site);
        }
        
        if (isset($data->telephone)) {
            $company->setTelephone($data->telephone);
        } 

        if (isset($data->email)) {
            $company->setEmail($data->email);
        }

        if (isset($logotype)) {
            $originalName = $logotype->getClientOriginalName();
            $components = explode('.', $originalName);
            $fileName = md5(time()) . '.' . end($components);
            
            $file = Image::make($logotype->getPathName())->grab(80);
            $file->save("public/img/logotypes/" . $fileName );
            $company->setLogotype($fileName);
        }

        try {

            $em->persist($company);
            $em->flush();
            return $company;

        } catch (Exception $e) {

          echo $e->getMessage();
          
        }
    }
}
