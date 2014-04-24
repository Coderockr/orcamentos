<?php

namespace Orcamentos\Service;

use Orcamentos\Model\Resource as ResourceModel;
use Exception;

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
    public static function save($data, $em)
    {
        $data = json_decode($data);

        if (!isset($data->name) || !isset($data->cost) || !isset($data->type) || !isset($data->companyId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $type = $em->getRepository("Orcamentos\Model\Type")->find($data->type);
        
        if ( $data->id ){
            $resource = $em->getRepository("Orcamentos\Model\Resource")->find($data->id);
        } else {
            $resource = new ResourceModel();
        }

        $resource->setName($data->name);
        $resource->setCost($data->cost);
        $resource->setType($type);

        if (isset($data->equipmentLife)) {
            $resource->setEquipmentLife($data->equipmentLife);
        }
        
        $company = $em->getRepository('Orcamentos\Model\Company')->find($data->companyId);
        
        if (isset($company)) {
            $resource->setCompany($company);
        }

        $em->persist($resource);
        $em->flush();

        return $resource;
    }

    /**
     * Function thatloads a company resources
     *
     * @return                Function used to load the Resources
     */
    public  function load($data, $em)
    {
        $data = json_decode($data);

        if (!isset($data->companyId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $company = $em->getRepository("Orcamentos\Model\Company")->find($data->companyId);

        $resources = $company->getResourceCollection();

        $equipmentResources = array();
        $serviceResources = array();
        $humanResources = array();

        foreach ($resources as $resource) {
            $type = $resource->getType();
            $typename = $type->getName();
            $array = array(
                'name' => $resource->getName(),
                'cost' => $resource->getCost(),
                'equipmentLife' => $resource->getEquipmentLife(),
                'type' => array(
                    'name' => $typename
                ),
                'id' => $resource->getId()
            );

            switch (get_class($type)) {
                case 'Orcamentos\Model\EquipmentType':
                    $equipmentResources[] = $array;
                    break;

                case 'Orcamentos\Model\ServiceType':
                    $serviceResources[] = $array;
                    break;

                case 'Orcamentos\Model\HumanType':
                    $humanResources[] = $array;
                    break;
             };
        }

        return array(
            'equipmentResources' => $equipmentResources,
            'serviceResources' => $serviceResources,
            'humanResources' => $humanResources,
        );
    }
}
