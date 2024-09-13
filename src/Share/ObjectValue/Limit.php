<?php
declare(strict_types=1);

namespace App\Share\ObjectValue;

use Webmozart\Assert\Assert;

class Limit
{
    private int $value;
    public function __construct(int $limit) {
        Assert::positiveInteger($limit);
        $this->value = $limit;
    }
    public function getValue(): int {
        return $this->value;
    }
}