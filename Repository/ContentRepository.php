<?php

namespace Wizin\Bundle\SimpleCmsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Wizin\Bundle\SimpleCmsBundle\Entity\Content;

/**
 * ContentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContentRepository extends EntityRepository
{
    /**
     * @param string $pathInfo
     * @return null|\Wizin\Bundle\SimpleCmsBundle\Entity\Content
     */
    public function retrieveEnableContent($pathInfo)
    {
        $criteria = [
            'pathInfo' => $pathInfo,
            'active' => true,
        ];

        return $this->findOneBy($criteria);
    }

    /**
     * @param Content $content
     * @return bool
     */
    public function isDuplicated(Content $content)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('content')
            ->from($this->getClassName(), 'content')
            ->where('content.pathInfo = :pathInfo')
            ->setParameter('pathInfo', $content->getPathInfo())
        ;
        if (is_null($content->getId()) === false) {
            $queryBuilder
                ->andWhere('content.id != :id')
                ->setParameter('id', $content->getId())
            ;
        }
        $entity = $queryBuilder->getQuery()->getOneOrNullResult();

        return (is_null($entity) === false);
    }
}
