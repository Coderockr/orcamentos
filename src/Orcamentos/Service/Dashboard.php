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
class Dashboard extends Service
{
    /**
     * Function that gets the last 10 updates
     * @param                 array $data
     * @return                array $updates
     */
    public function getData($data)
    {
        $data = json_decode($data);

        if (!isset($data->companyId)) {
            throw new Exception("Invalid Parameters", 1);
        }


        $limit = null;
        
        if(isset($data->limit)){
            $limit = $data->limit;
        }

        if (!$limit) {
            $limit = 50;
        }

        $userNotes = array();
        $query = $this->em->createQuery("SELECT pn FROM Orcamentos\Model\PrivateNote pn JOIN pn.project p JOIN p.company c WHERE c.id = ?1 ORDER BY pn.created DESC");
        $query->setParameter(1, $data->companyId);
        $query->setMaxResults($limit);
        $userNotes = $query->getResult();

        $clientNotes = array();
        $query = $this->em->createQuery(
            "SELECT sn FROM Orcamentos\Model\ShareNote sn JOIN sn.share s JOIN s.quote q JOIN q.project p JOIN p.company c WHERE c.id = ?1 ORDER BY sn.created DESC"
        );
        $query->setParameter(1, $data->companyId);
        $query->setMaxResults($limit);
        $clientNotes = $query->getResult();

        $clientView = array();
        $query = $this->em->createQuery(
            "SELECT v FROM Orcamentos\Model\View v JOIN v.share s JOIN s.quote q JOIN q.project p JOIN p.company c WHERE c.id = ?1 ORDER BY v.created DESC"
        );
        $query->setParameter(1, $data->companyId);
        $query->setMaxResults($limit);
        $clientViews = $query->getResult();

        $updates = array_merge($userNotes, $clientNotes, $clientViews);

        usort($updates, function ($a, $b)
        {
            if ($a->getCreated() == $b->getCreated()) {
                return 0;
            }
            return ($a->getCreated()  < $b->getCreated() ) ? 1 : -1;
        });

        $updates = array_slice($updates, 0, $limit);

        return $updates;
    }
}
