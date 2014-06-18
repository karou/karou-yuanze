<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AolsurveyRepository extends EntityRepository {

    public function findOneBySurveyInstanceID($survey_id) {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('a')
                ->from('AlbatrossAceBundle:Aolsurvey', 'a')
                ->where('a.SurveyInstanceID = :sid')
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
