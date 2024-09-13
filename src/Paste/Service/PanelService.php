<?php
declare(strict_types=1);

namespace App\Paste\Service;

use App\Paste\Query\PanelQuery;
use App\Share\ObjectValue\Limit;
use App\Share\ObjectValue\Page;
use App\Share\ObjectValue\Range;

class PanelService
{
    public function __construct(private PanelQuery $query, private PasteLinkCreator $linkCreator)
    {
    }

    public function getPasteData(Page $page, Limit $limit, \DateTimeImmutable $now): array
    {
        $range = Range::createFromPageAndLimit($page, $limit);
        $date = $this->query->getLastPublicPastesPaginate($range, $now);
        foreach ($date['data'] as &$paste) {
            $paste['link'] = $this->linkCreator->getFromHash($paste['hash']);
        }
        $date['page'] = $page->getValue();
        return $date;
    }
}