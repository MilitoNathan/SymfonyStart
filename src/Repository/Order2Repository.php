<?php

// src/Repository/Order2Repository.php

namespace App\Repository;

use App\Entity\Order2;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
* @method Order2|null find($id, $lockMode = null, $lockVersion = null)
* @method Order2|null findOneBy(array $criteria, array $Order2By = null)
* @method Order2[]    findAll()
* @method Order2[]    findBy(array $criteria, array $Order2By = null, $limit = null, $offset = null)
*/

class Order2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order2::class);
    }
}

