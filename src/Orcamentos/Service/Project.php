<?php

namespace Orcamentos\Service;

use Orcamentos\Model\Project as ProjectModel;
use Orcamentos\Model\PrivateNote as PrivateNoteModel;
use Exception;

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

        $em->persist($project);
        $em->flush();

        return $client;
    }



    /**
     * Function that saves a new Private
     *
     * @return                Function used to save a new Private
     */
    public static function comment($data, $em)
    {
        $data = json_decode($data);
        if (!isset($data->note) || !isset($data->projectId) || !isset($data->userId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $project = $em->getRepository("Orcamentos\Model\Project")->find($data->projectId);
        $user = $em->getRepository("Orcamentos\Model\User")->find($data->userId);

        $note = new PrivateNoteModel();
        $note->setProject($project);
        $note->setUser($user);
        $note->setNote($data->note);
        
        $em->persist($note);

        $em->flush();

        return $note;
    }
}
