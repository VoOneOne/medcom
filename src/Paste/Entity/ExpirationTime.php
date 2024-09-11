<?php
declare(strict_types=1);

namespace App\Paste\Entity;

enum ExpirationTime: int
{
    case WITHOUT_LIMIT = 0;
    case TEN_MIN = 10;
    case HOUR = 60;
    case DAY = 86400; // = 24 * 60 * 60;
    case WEEK = 604800; // = 7 * 24 * 60 * 60
    case MONTH = 18144000; // = 30 * 7 * 24 * 60 * 60;
}