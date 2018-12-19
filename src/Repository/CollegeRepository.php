<?php

namespace App\Repository;

use App\Entity\College;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method College|null find($id, $lockMode = null, $lockVersion = null)
 * @method College|null findOneBy(array $criteria, array $orderBy = null)
 * @method College[]    findAll()
 * @method College[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollegeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, College::class);
    }
    
    public function fetchAllHydrated()
    {
        return $this->createQueryBuilder('college')
            ->select(['college', 'school', 'department'])
            ->leftJoin('college.departments', 'department')
            ->leftJoin('college.schools', 'school')
            ->getQuery()
            ->getResult();
    }
}
