<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AttachmentsRepository extends EntityRepository {

    public function findLastOneByParentID($parent_id) {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('a')
                ->from('AlbatrossAceBundle:Attachments', 'a')
                ->where('a.parent = :pid')
                ->setMaxResults(1)
                ->orderBy('a.id', 'DESC');
        $qb->setParameters(array(
            'pid' => $parent_id
        ));

        $query = $qb->getQuery();
        $result = $query->getResult();
        foreach ($result as $re) {
            $result = $re;
        }
        return $result;
    }

    public function findFirstOneByParentID($parent_id) {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('a')
                ->from('AlbatrossAceBundle:Attachments', 'a')
                ->where('a.id = :pid')
                ->setMaxResults(1)
                ->orderBy('a.id', 'DESC');
        $qb->setParameters(array(
            'pid' => $parent_id
        ));

        $query = $qb->getQuery();
        $result = $query->getResult();
        foreach ($result as $re) {
            $result = $re;
        }
        return $result;
    }
    
    public function findLastOneByParentIDArr($parent_id) {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('a', 'info', 'bu')
                ->from('AlbatrossAceBundle:Attachments', 'a')
                ->leftJoin('a.attachinfo', 'info')
                ->leftJoin('info.bu', 'bu')
                ->where('a.parent = :pid')
                ->setMaxResults(1)
                ->orderBy('a.id', 'DESC');
        $qb->setParameters(array(
            'pid' => $parent_id
        ));

        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        foreach ($result as $re) {
            $result = $re;
        }
        return $result;
    }
}
