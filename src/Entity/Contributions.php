<?php

namespace App\Entity;

use App\Repository\ContributionsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ContributionsRepository::class)]
class Contributions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['contribution:read-all'])]

    private ?int $id = null;
    #[Groups(['contribution:read-all'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $contributions = null;

    #[ORM\ManyToOne(inversedBy: 'contributions')]
    #[Groups(['contribution:read-all'])]
    private ?Event $event = null;
    #[Groups(['contribution:read-all'])]
    #[ORM\ManyToOne(inversedBy: 'contributions')]
    private ?Profile $contributor = null;

    #[ORM\Column]
    private ?bool $suggestion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContributions(): ?string
    {
        return $this->contributions;
    }

    public function setContributions(string $contributions): static
    {
        $this->contributions = $contributions;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getContributor(): ?Profile
    {
        return $this->contributor;
    }

    public function setContributor(?Profile $contributor): static
    {
        $this->contributor = $contributor;

        return $this;
    }

    public function isSuggestion(): ?bool
    {
        return $this->suggestion;
    }

    public function setSuggestion(bool $suggestion): static
    {
        $this->suggestion = $suggestion;

        return $this;
    }
}
