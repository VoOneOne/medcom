<?php
declare(strict_types=1);

namespace App\Paste\Service;

use App\Paste\Entity\Paste;

class PasteLinkCreator
{
    public function __construct(private string $baseUrl)
    {
    }

    public function getLink(Paste $paste): string
    {
        return sprintf('%s/%s', $this->baseUrl, $paste->getHash());
    }
}