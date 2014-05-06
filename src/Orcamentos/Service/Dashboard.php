<?php

namespace Orcamentos\Service;

use Exception;

/**
 * Project Entity
 *
 * @category Orcamentos
 * @package Service
 * @author  Mateus Guerra<mateus@coderockr.com>
 */
class Dashboard
{
    /**
     * Function that gets the last 10 updates
     *
     * @return                
     */
    public static function getData($data, $em)
    {
        $data = json_decode($data);

        if (!isset($data->companyId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $limit = $data->limit;

        if (!$limit) {
            $limit = 10;
        }

        $userNotes = array();
        $query = $em->createQuery("SELECT pn FROM Orcamentos\Model\PrivateNote pn JOIN pn.project p JOIN p.company c WHERE c.id = ?1 ORDER BY pn.created DESC");
        $query->setParameter(1, $data->companyId);
        $query->setMaxResults($limit);
        $userNotes = $query->getResult();

        $clientNotes = array();
        $query = $em->createQuery(
            "SELECT sn FROM Orcamentos\Model\ShareNote sn JOIN sn.share s JOIN s.quote q JOIN q.project p JOIN p.company c WHERE c.id = ?1 ORDER BY sn.created DESC"
        );
        $query->setParameter(1, $data->companyId);
        $query->setMaxResults($limit);
        $clientNotes = $query->getResult();

        $clientView = array();
        $query = $em->createQuery(
            "SELECT v FROM Orcamentos\Model\View v JOIN v.share s JOIN s.quote q JOIN q.project p JOIN p.company c WHERE c.id = ?1 ORDER BY v.created DESC"
        );
        $query->setParameter(1, $data->companyId);
        $query->setMaxResults($limit);
        $clientViews = $query->getResult();

        $result = array_merge($userNotes, $clientNotes, $clientViews);

        usort($result, function ($a, $b)
        {
            if ($a->getCreated() == $b->getCreated()) {
                return 0;
            }
            return ($a->getCreated()  < $b->getCreated() ) ? 1 : -1;
        });

        $result = array_slice($result, 0, $limit);

        return $result;
    }
}
