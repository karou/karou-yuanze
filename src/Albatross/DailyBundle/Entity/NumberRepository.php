<?php 

namespace Albatross\DailyBundle\Entity;

use Doctrine\ORM\EntityRepository;

class NumberRepository extends EntityRepository {
    
    public function findOneByDateAndStatus($date, $status){
        $qb = $this->_em->createQueryBuilder();
        
        
        $qb->select('n')
            ->from('AlbatrossDailyBundle:Number', 'n')
            ->leftJoin('n.date', 'd')
            ->leftJoin('n.status', 's')
            ->where('d.id = :date')
            ->andWhere('s.id = :status')
            ->setMaxResults(1);
        $qb->setParameters(array(
                'date' => $date,
                'status' => $status
        ));
        
        $query = $qb->getQuery();
        $result = $query->getResult();
        foreach($result as $re){
            $result = $re;
        }
        
        return $result;
    }
    
}
