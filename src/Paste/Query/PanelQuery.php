<?php
declare(strict_types=1);

namespace App\Paste\Query;

use App\Paste\Entity\Paste;
use Doctrine\ORM\EntityManagerInterface;

class PanelQuery
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    public function getLastPublicPastes(int $count, \DateTimeImmutable $now): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        return $qb
            ->select('p.name', 'p.hash' )
            ->from(Paste::class, 'p')
            ->where(
                $qb->expr()->andX(
                    "p.access = 'public'",
                    $qb->expr()->orX('p.expirationDate > :now', $qb->expr()->isNull('p.expirationDate'))
                )
            )
            ->orderBy('p.createdAt')
            ->setMaxResults($count)
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult()
        ;
    }
}