<?php

namespace App\Paste\DataFixtures;

use App\Paste\Entity\Access;
use App\Paste\Entity\ExpirationTime;
use App\Paste\Entity\Language;
use App\Paste\Entity\Paste;
use App\Paste\Service\HashService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    public function __construct(private HashService $hashService){}

    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i < 100; $i++){
            $paste = new Paste(Uuid::v4());
            $paste->setText('text' . $i);
            $paste->setName('name' . $i);
            $paste->setHash($this->hashService->getHash(8));
            $paste->setAccess(Access::Public);
            $paste->setLanguage(Language::PHP);
            $paste->setExpirationTime(ExpirationTime::WITHOUT_LIMIT);
            $paste->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($paste);
        }
        $manager->flush();
    }
}
