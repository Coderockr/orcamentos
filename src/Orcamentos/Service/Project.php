<?php

namespace Orcamentos\Service;

use Orcamentos\Model\Project as ProjectModel;
use Intervention\Image\Image;
  
/**
 * Project Entity
 *
 * @category Orcamentos
 * @package Service
 * @author  Mateus Guerra<mateus@coderockr.com>
 */
class Project
{
    /**
     * Function that saves a new Project
     *
     * @return                Function used to save a new Project
     */
    public static function save($data, $em)
    {
        $data = json_decode($data);

        if (!isset($data->name) || !isset($data->tags) || !isset($data->client) || !isset($data->description) || !isset($data->companyId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $project = null;

        if ( isset($data->id) ) {
            $project = $em->getRepository("Orcamentos\Model\Project")->find($data->id);
        }

        if (!$project) {
            $project = new ProjectModel();
        }

        $project->setName($data->name);
        $project->setTags($data->tags);
        $project->setDescription($data->description);
        
        $client = $em->getRepository("Orcamentos\Model\Client")->find($data->client);

        if (!$client) {
            throw new Exception("Cliente inválido", 1);
        }
        
        $project->setClient($client);
        
        $company = $em->getRepository('Orcamentos\Model\Company')->find($data->companyId);
        
        if (isset($company)) {
            $project->setCompany($company);
        }

        if (isset($data->privateNotes)) {
            $project->setPrivateNotes($data->privateNotes);
        }
      
        if (isset($data->clientNotes)) {
            $project->setClientNotes($data->clientNotes);
        }
      
        $em->persist($project);
        $em->flush();

        return $client;
    }
}
