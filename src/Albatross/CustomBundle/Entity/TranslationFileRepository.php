<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TranslationFileRepository extends EntityRepository {

    public function findLastOneByFileIndexLanguageNum($customFieldId, $fileIndex, $languageNum) {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('translation_file')
                ->from('AlbatrossCustomBundle:TranslationFile', 'translation_file')
                ->leftJoin('translation_file.customfield', 'custom_field')
                ->where('custom_field.id = :cf_id')
                ->andWhere('translation_file.file_index = :f_index')
                ->andWhere('translation_file.language_index = :l_index');
        $qb->setParameters(array(
            'cf_id' => $customFieldId,
            'f_index' => $fileIndex,
            'l_index' => $languageNum
        ));

        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }

}
