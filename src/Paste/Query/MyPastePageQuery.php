<?php
declare(strict_types=1);

namespace App\Paste\Query;

use App\Entity\PasteUser;
use App\Paste\Entity\Paste;
use App\Share\ObjectValue\Range;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class MyPastePageQuery
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getMyPastes(Uuid $authUserUuid, Range $range, \DateTimeImmutable $now): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $data = $qb
            ->from(PasteUser::class, 'pu')
            ->leftJoin(Paste::class, 'p')
            ->select('p.name', 'p.hash', 'IDENTITY(pu.user) as authUserId')
            ->andWhere('authUserId = :uuid')
            ->andWhere('p.expirationDate > :now OR p.expirationDate is NULL')
            ->setParameter('now', $now)
            ->setParameter('uuid', $authUserUuid->toBinary())
            ->orderBy('p.createdAt')
            ->setFirstResult($range->getMin())
            ->setMaxResults($range->getMax() + 1)
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