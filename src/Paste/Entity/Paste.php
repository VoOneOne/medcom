<?php
declare(strict_types=1);

namespace App\Paste\Entity;


use App\Entity\PasteUser;
use App\Paste\Repository\PasteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PasteRepository::class)]
class Paste
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: 'integer', nullable: false, enumType: ExpirationTime::class)]
    private ExpirationTime $expirationTime;

    #[ORM\Column(type: 'string', nullable: false, enumType: Access::class)]
    private Access $access;
    #[ORM\Column(type: 'string', nullable: false, enumType: Language::class)]
    private Language $language;

    #[ORM\Column(nullable: false)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expirationDate = null;

    #[ORM\Column(length: 8)]
    private ?string $hash = null;

    #[ORM\ManyToOne(targetEntity: PasteUser::class, cascade: ['persist'], inversedBy: 'pastes')]
    private ?PasteUser $user = null;

    public function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    public function getId(): Uuid
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

    public function getExpirationTime(): ExpirationTime
    {
        return $this->expirationTime;
    }
    public function setExpirationTime(ExpirationTime $expirationTime): void
    {
        $this->expirationTime = $expirationTime;
    }
    public function getAccess(): Access
    {
        return $this->access;
    }

    public function setAccess(Access $access): void
    {
        $this->access = $access;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function setLanguage(Language $language): void
    {
        $this->language = $language;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
        if (empty($this->expirationTime)) {
            throw new \DomainException(
                sprintf('Invalid %s object. Expected expirationTime init', self::class)
            );
        }
        if ($this->expirationTime !== ExpirationTime::WITHOUT_LIMIT) {
            $interval = sprintf('PT%dM', $this->expirationTime->value);
            $dateInterval = new \DateInterval($interval);
            $this->expirationDate = $createdAt->add($dateInterval);
        }
    }
    public function getExpirationDate(): ?\DateTimeImmutable
    {
        return $this->expirationDate;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): static
    {
        $this->hash = $hash;

        return $this;
    }
    public function isExpired(\DateTimeImmutable $now): bool
    {
        if(is_null($this->expirationDate)){
            return false;
        }
        return $this->expirationDate < $now;
    }

    public function getUser(): ?PasteUser
    {
        return $this->user;
    }

    public function setUser(?PasteUser $user): static
    {
        $this->user = $user;

        return $this;
    }
}
