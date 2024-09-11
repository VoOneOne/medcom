<?php
declare(strict_types=1);

namespace App\Paste\Entity;

use App\Paste\PasteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PasteRepository::class)]
class Paste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: 'integer', nullable: false, enumType: ExpirationTime::class)]
    private ExpirationTime $expirationTime = ExpirationTime::WITHOUT_LIMIT;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }
    public function getHash(): string
    {
        return (string)$this->id;
    }

    public function getExpirationTime(): ?ExpirationTime
    {
        return $this->expirationTime;
    }

    public function setExpirationTime(?ExpirationTime $expirationTime): void
    {
        $this->expirationTime = $expirationTime;
    }
}
