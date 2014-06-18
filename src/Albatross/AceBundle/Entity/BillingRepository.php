<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\EntityRepository;

class BillingRepository extends EntityRepository {

    public function findOneBySurveyId($survey_id) {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('b')
                ->from('AlbatrossAceBundle:Billing', 'b')
                ->leftJoin('b.aolsurvey', 'a')
                ->where('a.id = :sid')
                ->setMaxResults(1);
        $qb->setParameters(array(
            'sid' => $survey_id
        ));

        $query = $qb->getQuery();
        $result = $query->getResult();
        foreach ($result as $re) {
            $result = $re;
        }

        return $result;
    }

}
