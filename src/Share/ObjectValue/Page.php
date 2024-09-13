<?php
declare(strict_types=1);

namespace App\Share\ObjectValue;

use Webmozart\Assert\Assert;

class Page
{
    private int $value;
    public function __construct(int $offset) {
        Assert::positiveInteger($offset);
        $this->value = $offset;
    }
    public function getValue(): int {
        return $this->value;
    }
}