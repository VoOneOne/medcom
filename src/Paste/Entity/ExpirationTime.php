<?php
declare(strict_types=1);

namespace App\Paste\Entity;

enum ExpirationTime: int
{
    case WITHOUT_LIMIT = 0;
    case HOUR = 60;
    case DAY = 1440; // = 24 * 60;
    case WEEK = 10080; // = 7 * 24 * 60
    case MONTH = 43200; // = 30 * 24 * 60;
}