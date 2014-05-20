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
class Resource extends Service
{
    /**
     * Function that saves a new Resource
     * @param                 array $data
     * @return                Orcamentos\Model\Resource $resource
     */
    public function save($data)
    {
        $data = json_decode($data);

        if (!isset($data->name) || !isset($data->cost) || !isset($data->type) || !isset($data->companyId)) {
            throw new Exception("Parâmetros inválidos", 1);
        }

        $type = $this->em->getRepository("Orcamentos\Model\Type")->find($data->type);
        
        $resource = $this->getResource($data);

        $resource->setName($data->name);
        $data->cost = str_replace(',', '.', $data->cost);
        $resource->setCost($data->cost);
        $resource->setType($type);

        if (isset($data->equipmentLife)) {
            $resource->setEquipmentLife($data->equipmentLife);
        }
        
        $company = $this->em->getRepository('Orcamentos\Model\Company')->find($data->companyId);
        
        if (!isset($company)) {
            throw new Exception("Empresa não encontrada", 1);
        }

        $resource->setCompany($company);
        
        try {

            $this->em->persist($resource);
            $this->em->flush();
            return $resource;
         
         } catch (Exception $e) {
         
            echo $e->getMessage();
        
        }
    }

    /**
     * Function used to get a already saved or a new Resource Object
     * @param                 array $data
     * @return                Orcamentos\Model\Resource $resource
     */
    private function getResource($data){

        $resource = null;

        if ( isset($data->id) ) {
            $resource = $this->em->getRepository("Orcamentos\Model\Resource")->find($data->id);
        }

        if (!$resource) {
            $resource = new ResourceModel();
        }

        return $resource;
    }


    /**
     * Function that gets a company resources
     * @param                 array $data
     * @return                array
     */
    public function get($data)
    {
        $data = json_decode($data);

        if (!isset($data->companyId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $company = $this->em->getRepository("Orcamentos\Model\Company")->find($data->companyId);

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
