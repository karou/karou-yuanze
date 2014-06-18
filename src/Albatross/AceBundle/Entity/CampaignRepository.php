<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CampaignRepository extends EntityRepository {

    public function findOneByNameAndQuestionnaire($campaign, $questionnaire) {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('c')
                ->from('AlbatrossAceBundle:Campaign', 'c')
                ->leftJoin('c.questionnaire', 'q')
                ->where('q.name = :questionnaire AND c.name = :campaign')
                ->setMaxResults(1);
        $qb->setParameters(array(
            'questionnaire' => $questionnaire,
            'campaign' => $campaign
        ));

        $query = $qb->getQuery();
        $result = $query->getResult();
        foreach ($result as $re) {
            $result = $re;
        }

        return $result;
    }

}
