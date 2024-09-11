<?php
declare(strict_types=1);

namespace App\Paste\Entity;

enum Language: string
{
    case Empty = 'empty';
    case PHP = 'PHP';
}