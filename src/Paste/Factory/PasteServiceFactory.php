<?php
declare(strict_types=1);

namespace App\Paste\Factory;

use App\Paste\Service\PasteAnonymHandler;
use App\Paste\Service\PasteSessionService;
use Symfony\Component\Security\Core\User\UserInterface;

class PasteServiceFactory
{
    public function __construct(private PasteSessionService $pasteSessionService)
    {}
    public function createPasteHandler(?UserInterface $user): PasteAnonymHandler {
        return $isAuth ? new PasteAnonymHandler($this->pasteSessionService) : new PasteAnonymHandler($this->pasteSessionService);
    }
}