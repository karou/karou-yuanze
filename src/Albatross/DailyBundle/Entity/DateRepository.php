<?php

namespace Albatross\DailyBundle\Entity;

use Doctrine\ORM\EntityRepository;

class DateRepository extends EntityRepository {

    public function findOneByDailydateAndBu($date, $bu) {
        $qb = $this->_em->createQueryBuilder();

        if ($bu == null || $bu == 0) {
            $qb->select('d')
                    ->from('AlbatrossDailyBundle:Date', 'd')
                    ->leftJoin('d.bu', 'b')
                    ->where('d.dailydate = :date')
                    ->andWhere('b.id is null')
                    ->setMaxResults(1);
            $qb->setParameters(array(
                'date' => $date
            ));
        } else {
            $qb->select('d')
                    ->from('AlbatrossDailyBundle:Date', 'd')
                    ->leftJoin('d.bu', 'b')
                    ->where('d.dailydate = :date')
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

    public function findLastDateWithForecastByBu($bu) {
        $qb = $this->_em->createQueryBuilder();

        if ($bu == null || $bu == 0) {
            $qb->select('d')
                    ->from('AlbatrossDailyBundle:Date', 'd')
                    ->leftJoin('d.bu', 'b')
                    ->where('b.id is null')
                    ->andWhere('d.forecast IS NOT NULL')
                    ->orderBy('d.dailydate', 'DESC')
                    ->setMaxResults(1);
        } else {
            $qb->select('d')
                    ->from('AlbatrossDailyBundle:Date', 'd')
                    ->leftJoin('d.bu', 'b')
                    ->where('b.id = :bu')
                    ->andWhere('d.forecast IS NOT NULL')
                    ->orderBy('d.dailydate', 'DESC')
                    ->setMaxResults(1);
            $qb->setParameters(array(
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
