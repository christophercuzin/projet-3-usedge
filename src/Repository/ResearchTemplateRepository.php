<?php

namespace App\Repository;

use App\Entity\ResearchTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResearchTemplate>
 *
 * @method ResearchTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResearchTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResearchTemplate[]    findAll()
 * @method ResearchTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResearchTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResearchTemplate::class);
    }

    public function add(ResearchTemplate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ResearchTemplate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function updateTemplateStatus(array $dataComponent): void
    {
        $templateStatus = $dataComponent['research-template-status'];
        $templateId = $dataComponent['research-template-id'];
        $statusToSave = $this->findOneBy(['id' => $templateId]);
        if ($statusToSave instanceof ResearchTemplate) {
            $statusToSave->setStatus($templateStatus);
            $this->add($statusToSave, true);
        }
    }

    public function findByStatus(): mixed
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.status != :archive')
            ->setParameter('archive', 'archive')
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByArchiveStatus(): mixed
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.status = :archive')
            ->setParameter('archive', 'archive')
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return ResearchTemplate[] Returns an array of ResearchTemplate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ResearchTemplate
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
