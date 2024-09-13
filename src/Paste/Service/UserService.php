<?php
declare(strict_types=1);

namespace App\Paste\Service;

use App\Entity\PasteUser;
use App\Paste\Entity\Paste;
use App\Repository\PasteUserRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

;

class UserService
{
    public function __construct(
        private PasteSessionService $pasteSessionService,
        private UserRepository $userRepository,
        private PasteUserRepository $pasteUserRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function connect(?UserInterface $user, Paste $paste): void
    {
        if ($user) {
            $uuid = Uuid::fromString($user->getUserIdentifier());
            /**
             * @var PasteUser $pasteUser
             */
            $pasteUser = $this->pasteUserRepository->findOneBy(['user' => $user->getUserIdentifier()]);
            if(is_null($pasteUser)) {
                $authUser = $this->userRepository->findOneBy(['id' => $user->getUserIdentifier()]);
                $pasteUser = new PasteUser();
                $pasteUser->setUser($authUser);
                $this->entityManager->persist($pasteUser);
            }
            $pasteUser->addPaste($paste);
        } else {
            $this->pasteSessionService->addPasteId($paste->getId());
        }
    }
}