<?php
declare(strict_types=1);

namespace App\Paste\Entity;

enum Access: string
{
    case Public = "public";
    case Unlisted = "unlisted";
    case Private = "private";
}