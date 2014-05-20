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
class Project extends Service
{
    /**
     * Function that saves a new Project
     * @param                 array $data
     * @return                Orcamentos\Model\Project $project
     */
    public function save($data)
    {
        $data = json_decode($data);

        if (!isset($data->name) || !isset($data->tags) || !isset($data->client) || !isset($data->description) || !isset($data->companyId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $project = $this->getProject($data);

        $project->setName($data->name);
        $project->setTags($data->tags);
        $project->setDescription($data->description);
        
        $client = $this->em->getRepository("Orcamentos\Model\Client")->find($data->client);

        if (!$client) {
            throw new Exception("Cliente inválido", 1);
        }
        
        $project->setClient($client);
        
        $company = $this->em->getRepository('Orcamentos\Model\Company')->find($data->companyId);
        
        if (!isset($company)) {
            throw new Exception("Empresa não encontrada", 1);
        }
        
        $project->setCompany($company);

        try {

            $this->em->persist($project);
            $this->em->flush();
            return $project;

        } catch (Exception $e) {

          echo $e->getMessage();

        }
    }

    /**
     * Function used to get a already saved or a new Project Object
     * @param                 array $data
     * @return                Orcamentos\Model\Project $project
     */
    private function getProject($data){

        $project = null;

        if ( isset($data->id) ) {
            $project = $this->em->getRepository("Orcamentos\Model\Project")->find($data->id);
        }

        if (!$project) {
            $project = new ProjectModel();
        }

        return $project;
    }


    /**
     * Function that searches projetcs
     * @param                 array $data
     * @return                Query $query
     */
    public function search($data)
    {
        $data = json_decode($data);

        if (!isset($data->query) || !isset($data->companyId)) {
            throw new Exception("Invalid Parameters", 1);
        }
        $company = $this->em->getRepository('Orcamentos\Model\Company')->find($data->companyId);

        $query = $this->em->getRepository("Orcamentos\Model\Project")->createQueryBuilder('p')
           ->where('p.company = :company')
           ->andWhere('p.name LIKE :query')
           ->setParameter('company', $company )
           ->setParameter('query', '%'. $data->query.'%')
           ->getQuery();

        $this->em->flush();

        return $query;
    }


    /**
     * Function that saves a new Private message
     * @param                 array $data
     * @return                Orcamentos\Model\PrivateNote $note
     */
    public function comment($data)
    {
        $data = json_decode($data);
        if (!isset($data->note) || !isset($data->projectId) || !isset($data->userId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $project = $this->em->getRepository("Orcamentos\Model\Project")->find($data->projectId);
        $user = $this->em->getRepository("Orcamentos\Model\User")->find($data->userId);

        $note = new PrivateNoteModel();
        $note->setProject($project);
        $note->setUser($user);
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
     * Function that deletes a Private message 
     * @param                 array $data
     * @return                Orcamentos\Model\PrivateNote $note
     */
    public function removeComment($data)
    {
        $data = json_decode($data);

        if ( !isset($data->noteId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $note = $this->em->getRepository("Orcamentos\Model\PrivateNote")->find($data->noteId);

        try {

            $this->em->remove($note);
            $this->em->flush();
            return $note;

        } catch (Exception $e) {

          echo $e->getMessage();

        }
    }
}
