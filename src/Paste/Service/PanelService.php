<?php
declare(strict_types=1);

namespace App\Paste\Service;

use App\Paste\Query\PanelQuery;

class PanelService
{
    public function __construct(private PanelQuery $query, private PasteLinkCreator $linkCreator)
    {
    }

    public function getLastPastes(\DateTimeImmutable $now): array
    {
        $pastes = $this->query->getLastPublicPastes(10, $now);
        foreach ($pastes as &$paste) {
            $paste['link'] = $this->linkCreator->getFromHash($paste['hash']);
        }
        return $pastes;
    }
}