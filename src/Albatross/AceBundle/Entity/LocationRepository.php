<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LocationRepository extends EntityRepository {

    public function findByLocationInfo($name, $id, $code) {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('loc')
                ->from('AlbatrossAceBundle:Location', 'loc')
                ->where('loc.LocStoreID = :id AND loc.LocName = :name AND loc.LocCountryCode = :code')
                ->setMaxResults(1);
        $qb->setParameters(array(
            'id' => $id,
            'name' => $name,
            'code' => $code
        ));

        $query = $qb->getQuery();
        $result = $query->getResult();
        foreach ($result as $re) {
            $result = $re;
        }

        return $result;
    }

}
