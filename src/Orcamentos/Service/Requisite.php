<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 15/11/15
 * Time: 01:41
 */
namespace Orcamentos\Service;

use Orcamentos\Model\Resource as ResourceModel;
use Orcamentos\Model\Requisite as RequesiteModel;
use Orcamentos\Model\ResourceQuote as ResourceQuoteModel;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use DateTime;
use Orcamentos\Service\Service;

/**
 * Requeriment Entity
 *
 * @category Orcamentos
 * @package Service
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class Requisite extends Service
{

    public function save($data)
    {
        $data = json_decode($data);

        if (!isset($data->name) || !isset($data->expectedAmount) || !isset($data->projectId)) {
            throw new Exception("Parâmetros inválidos", 1);
        }

        $requisite = $this->getRequisite($data);

        $requisite->setName($data->name);
        $requisite->setExpectedAmount($data->expectedAmount);
        $requisite->setDescription($data->description);
        $requisite->setSpentAmount($data->spentAmount);

        $project = $this->em->getRepository('Orcamentos\Model\Project')->find($data->projectId);
        if (!isset($project)) {
            throw new Exception("Projeto não encontrado", 1);
        }

        $requisite->setProject($project);

        try {

            $this->em->persist($requisite);
            $this->em->flush();

            return $requisite;
        } catch (Exception $e) {

            echo $e->getMessage();

        }

    }


    public function get($data)
    {
        $data = json_decode($data);
        $requisite = $this->em->getRepository('Orcamentos\Model\Requisite')->find($data);

        return array(
            'id' => $requisite->getId(),
            'name' => $requisite->getName(),
            'description' => $requisite->getDescription(),
            'expectedAmount' => $requisite->getExpectedAmount(),
            'spentAmount' => $requisite->getSpentAmount(),
            'projectId' => $requisite->getProject()->getId()
        );
    }

    /**
     * Function used to get a already saved or a new Requisite Object
     * @param                 array $data
     * @return                Orcamentos\Model\Requisite $requisite
     */
    private function getRequisite($data)
    {

        $requisite = null;

        if ( isset($data->id) ) {
            $requisite = $this->em->getRepository("Orcamentos\Model\Requisite")->find($data->id);
        }

        if (!$requisite) {
            $requisite = new RequesiteModel();
        }

        return $requisite;
    }



}