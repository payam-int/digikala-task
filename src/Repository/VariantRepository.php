<?php

namespace App\Repository;

use App\Entity\Variant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class VariantRepository extends ServiceEntityRepository implements ListRepositoryInterface
{
    use ListRepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Variant::class);
    }

}
