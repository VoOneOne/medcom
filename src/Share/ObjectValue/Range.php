<?php
declare(strict_types=1);

namespace App\Share\ObjectValue;

use Webmozart\Assert\Assert;

class Range
{
    public function __construct(private int $min, private int $max)
    {
        Assert::greaterThanEq($max, $min);
    }
    public static function createFromPageAndLimit(Page $page, Limit $limit): self
    {
        $min = ($page->getValue() - 1) * $limit->getValue();
        $max = $page->getValue() * $limit->getValue() - 1;
        return new self($min, $max);
    }

    public function getMin(): int
    {
        return $this->min;
    }

    public function getMax(): int
    {
        return $this->max;
    }
    public function getCount(): int
    {
        return $this->max - $this->min + 1;
    }
}