<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\EntityRepository;

class WorkflowRepository extends EntityRepository {

    public function findByIdAndStatus($wid, $wstatus) {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('w')
                ->from('AlbatrossAceBundle:Workflow', 'w')
                ->where('w.WorkflowStepID = :wid AND w.WorkflowStatus = :wstatus')
                ->setMaxResults(1);
        $qb->setParameters(array(
            'wid' => $wid,
            'wstatus' => $wstatus
        ));

        $query = $qb->getQuery();
        $result = $query->getResult();
        foreach ($result as $re) {
            $result = $re;
        }

        return $result;
    }

}
