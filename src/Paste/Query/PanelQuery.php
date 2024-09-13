<?php
declare(strict_types=1);

namespace App\Paste\Query;


use App\Paste\Entity\Paste;
use App\Share\ObjectValue\Range;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\Date;

class PanelQuery
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getLastPublicPastes(int $count, \DateTimeImmutable $now): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        return $qb
            ->select('p.name', 'p.hash')
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
            ->getResult();
    }
    public function getLastPublicPastesPaginate(Range $range, \DateTimeImmutable $now): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $data = $qb
            ->from(Paste::class, 'p')
            ->select('p.name', 'p.hash')
            ->where(
                $qb->expr()->andX(
                    "p.access = 'public'",
                    $qb->expr()->orX('p.expirationDate > :now', $qb->expr()->isNull('p.expirationDate'))
                )
            )
            ->setParameter('now', $now)
            ->orderBy('p.createdAt', 'DESC')
            ->setFirstResult($range->getMin())
            ->setMaxResults($range->getCount() + 1)
            ->getQuery()
            ->getArrayResult();
        if (count($data) > $range->getCount()) {
            array_pop($data);
            return [
                'data' => $data,
                'hasMore' => true
            ];
        }
        return [
            'data' => $data,
            'hasMore' => false
        ];
    }

}