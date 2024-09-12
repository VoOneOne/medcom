<?php
declare(strict_types=1);

namespace App\Paste\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Uuid;

class PasteSessionService
{
    public function __construct(private RequestStack $requestStack){}
    public function addPasteId(Uuid $id): void
    {
        $session = $this->requestStack->getSession();
        $pastes = $session->get('pastes', []);
        $pastes[] = $id->toString();
        $session->set('paste', $pastes);
    }
    public function getPastes(): array
    {
        $session = $this->requestStack->getSession();
        return $session->get('pastes', []);
    }
}