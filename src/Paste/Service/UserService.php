<?php
declare(strict_types=1);

namespace App\Paste\Service;

use App\Entity\User;
use App\Paste\Entity\Paste;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

;

class UserService
{
    public function __construct(private PasteSessionService $pasteSessionService)
    {
    }

    public function connect(?UserInterface $user, Paste $paste): void
    {
        if ($user) {
            $uuid = Uuid::fromString($user->getUserIdentifier());
            $paste->setAuthUser($uuid);
        } else {
            $this->pasteSessionService->addPasteId($paste->getId());
        }
    }
}