<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ForecastScopeRepository extends EntityRepository {

    public function findOneByMonthAndBu($date, $bu) {
        $qb = $this->_em->createQueryBuilder();

        if ($bu == null) {
            $qb->select('f')
                    ->from('AlbatrossAceBundle:ForecastScope', 'f')
                    ->leftJoin('f.bu', 'b')
                    ->where('f.month = :date')
                    ->andWhere('b.id is null')
                    ->setMaxResults(1);
            $qb->setParameters(array(
                'date' => $date
            ));
        } else {
            $qb->select('f')
                    ->from('AlbatrossAceBundle:ForecastScope', 'f')
                    ->leftJoin('f.bu', 'b')
                    ->where('f.month = :date')
                    ->andWhere('b.id = :bu')
                    ->setMaxResults(1);
            $qb->setParameters(array(
                'date' => $date,
                'bu' => $bu
            ));
        }

        $query = $qb->getQuery();
        $result = $query->getResult();
        foreach ($result as $re) {
            $result = $re;
        }

        return $result;
    }

}
