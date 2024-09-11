<?php
declare(strict_types=1);

namespace App\Paste\Service;

class PastLinkCreator
{
    public function getLink(string $pastHash): string
    {
        return sprintf('/%s', $pastHash);
    }
}