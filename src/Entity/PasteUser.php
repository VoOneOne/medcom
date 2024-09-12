<?php

namespace App\Entity;

use App\Repository\PasteUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PasteUserRepository::class)]
class PasteUser extends BaseUser
{

}
